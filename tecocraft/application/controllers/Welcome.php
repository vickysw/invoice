<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data = [];
		
		$this->form_validation->set_rules('text', 'Input', 'required');

		if ($this->form_validation->run() == FALSE) { 
		}else{
			$postData = explode(',',$this->input->post('text'));
			$count = count($postData);
			for ($i = 0; $i < $count; $i++) {
				for ($j = $i + 1; $j < $count; $j++) {
					if ($postData[$i] > $postData[$j]) {
						$temp = $postData[$i];
						$postData[$i] = $postData[$j];
						$postData[$j] = $temp;
					}
				}
			}
			$data['sortData'] = $postData;
		} 
		$this->load->view('welcome_message',$data);
	}

	/**
	 *  For List of  invoice 
	 * 
	 */
	public function listInvoice()
	{	
		$this->db->select();
		$data['invoices'] = $this->db->get('invoice')->result_array();

		$this->load->view('list_invoice',$data);
	}

	/**
	 *  For add invoice 
	 * 
	 */
	public function addInvoice()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name[]', 'Name', 'required');
		$this->form_validation->set_rules('description[]', 'Description', 'required');
		$this->form_validation->set_rules('quantity[]', 'Quantity', 'required|is_natural');
		$this->form_validation->set_rules('discount[]', 'Discount', 'required|decimal');
		$this->form_validation->set_rules('price[]', 'Price', 'required');

		if ($this->form_validation->run() == FALSE) { 
			$this->load->view('add_invoice');
		}else{
			$postData = $this->input->post();
			$insertData = [];
			foreach($postData['name'] as $key=>$value){
				$insertData[$key]['name'] = $postData['name'][$key];
				$insertData[$key]['description'] = $postData['description'][$key];
				$insertData[$key]['quantity'] = $postData['quantity'][$key];
				$insertData[$key]['price'] = $postData['price'][$key];
				$insertData[$key]['discount'] = $postData['discount'][$key];
			}

			$this->db->insert_batch('invoice',$insertData);

			redirect('welcome/listInvoice');
		} 
	}
}
