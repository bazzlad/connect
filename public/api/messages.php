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
			if (!isset($post['all'])) {
				$this->db->where('active', 1);
			} else {
				$this->db->orderBy('id', 'desc');
			}
			$this->db->orderBy('prio');
			$messages = $this->db->get('messages');

			$data = array(
				'result' => 'success', 
				'data' => $messages
			);
			
			return $response->withJson($data);
		break;
		case 'seen':
			// mark seen messages
			if (!isset($post['id'])) {
				$data = array(
					'result' => 'error', 
					'data' => 'ID is required'
				);
				return $response->withJson($data);
			}

			$this->db->where('id', $post['id']);
			$data = array (
				'active' => 0
			);
			$messages = $this->db->update('messages', $data);

			$data = array(
				'result' => 'success', 
				'data' => 'Message marked as seen'
			);

			// clean old records
			removeOldRecords($this->db);
			return $response->withJson($data);
		break;
		case 'add':
			if (!isset($post['active']) || 
				 !isset($post['message']) ||
				 !isset($post['prio'])
				) {
				$data = array(
					'result' => 'error', 
					'data' => 'Wrong variables sent'
				);
				return $response->withJson($data);
			}

			$data = array (
				'active' => $post['active'],
				'message' => $post['message'],
				'prio' => $post['prio']
			);
			$messages = $this->db->insert('messages', $data);

			$data = array(
				'result' => 'success', 
				'data' => 'Message added successfully'
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

// stop messages table growing indefinitely
function removeOldRecords($db) {
	$db->rawQuery('DELETE FROM messages WHERE timestamp < NOW() - INTERVAL 30 DAY;');
}

?>