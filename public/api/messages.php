<?php
	// Get POST data
	$post = (array)$request->getParsedBody();

	// needed
	if (!isset($post['type'])) {
		$data = array('result' => 'error', 'data' => 'No Type Sent');
		return $response->withJson($data);
	}

	$type = $post['type'];


	switch ($type) {
		case 'get':

			$this->db->where('active', 1);
			$this->db->orderBy('prio');
			$messages = $this->db->get('messages');

			$data = array(
				'result' => 'success', 
				'data' => $messages
			);
			
			return $response->withJson($data);
		break;
		default:
			$data = array('result' => 'error', 'data' => 'Type Not Supported');
			return $response->withJson($data);
		break;
	}



function findObjectById($array, $id) {
	foreach ($array as $i => $item) {
		if (isset($item['id'])) {
			if ($item['id'] == $id) {
				return $item;
			}
		}
	}
}

?>