<?php
// ✅ CSAS REST API EXTENSIONS

add_action('rest_api_init', function () {
	register_rest_route('csas/v1', '/subscribe', [
		'methods' => 'POST',
		'callback' => 'csas_api_subscribe',
		'permission_callback' => '__return_true'
	]);

	register_rest_route('csas/v1', '/unsubscribe', [
		'methods' => 'POST',
		'callback' => 'csas_api_unsubscribe',
		'permission_callback' => '__return_true'
	]);
});

function csas_api_subscribe($request) {
	$params = $request->get_json_params();
	$number = sanitize_text_field($params['number'] ?? '');

	if (!$number) {
		return new WP_REST_Response(['error' => 'Phone number required'], 400);
	}

	$list = get_option('csas_sms_recipients', []);
	if (!in_array($number, $list)) {
		$list[] = $number;
		update_option('csas_sms_recipients', $list);
		return ['success' => true, 'message' => 'Number subscribed'];
	}

	return ['success' => false, 'message' => 'Number already subscribed'];
}

function csas_api_unsubscribe($request) {
	$params = $request->get_json_params();
	$number = sanitize_text_field($params['number'] ?? '');

	if (!$number) {
		return new WP_REST_Response(['error' => 'Phone number required'], 400);
	}

	$list = get_option('csas_sms_recipients', []);
	$updated = array_filter($list, fn($n) => $n !== $number);
	update_option('csas_sms_recipients', array_values($updated));

	return ['success' => true, 'message' => 'Unsubscribed'];
}
