<?php defined('BASEPATH') OR exit('No direct script access allowed');
// Ajax.php
class Ajax extends Public_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('blog_m');
		$this->load->model('customer_m');
		$this->load->model('category_m');
		$this->load->model('product_m');
		$this->load->model('sale_m');
		$this->load->model('Shipment_express_m');
		$this->load->library('cart');
		$this->load->library('encrypt');
		$this->load->helper('shipping'); 
		$this->load->model('shippingv2_m');
	}

	//ajax product page select size
	public function ajax_select_size() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$quantity = (int) $this->input->post('quantity');
		$id_product = (int) $this->input->post('id_product');

        //get product base price
        $this->db->select('price')->from('products')->where('id_products', $id_product);
        $base_price = $this->db->get()->row()->price;

		//check if the id_product has quantity discount
		$this->db->select('id_quantity_discount')->from('quantity_discount')->where('product_id', $id_product);
		$count_quantity_discount = $this->db->get()->num_rows();

		if ($count_quantity_discount > 0) {

			//get discount for chosen quantity, choosing the closest quantity
			$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
			$row = $query->row();

			if (count($row) > 0) {

				$data['quantity_discounted_price'] = $base_price - ($base_price * $row->discount_percentage / 100);
				$data['quantity_discount_percentage'] = $row->discount_percentage;

			} else {

				//quantity is less than minimum discount rule
				//check if have base normal discount
				$this->db->select('discount_price')->from('products')->where('id_products', $id_product);
    			$discount_price = $this->db->get()->row()->discount_price;

				if ($discount_price != 0) {

					$data['discounted_price'] = $base_price - ($base_price * $discount_price / 100);
					$data['discount_percentage'] = $discount_price;
				}
			}

		} else {
			//no quantity discount
			//check if have base normal discount
			$this->db->select('discount_price')->from('products')->where('id_products', $id_product);
			$discount_price = $this->db->get()->row()->discount_price;

			if ($discount_price != 0) {

				$data['discounted_price'] = $base_price - ($base_price * $discount_price / 100);
				$data['discount_percentage'] = $discount_price;
			}
		}

		$data['price'] = $base_price;
		$data['id_product'] = $id_product;

		$this->load->view('ajax/ajax_select_size', $data);
	}



	//ajax product page add product review
	public function ajax_addproductreview() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		//CPATCHA VALIDATION
		// First, delete old captchas
		$expiration = time() - 7200; // Two hour limit
		$this->db->where('captcha_time < ', $expiration)
				->delete('captcha');

		// Then see if a captcha exists and match
		$sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
		$binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
		$query = $this->db->query($sql, $binds);
		$row = $query->row();

		if ($row->count == 0) {
			echo '<p style="background-color:red; color:white; padding:5px;">Mohon masukan kode yang benar.</p>';
			exit();
		}

		$product_id = (int) $this->input->post('product_id');
		$rating = $this->input->post('rating');
		$review = $this->security->xss_clean($this->input->post('review'));

		if($this->input->post('customer_id')) {
			//if customer act as a registered during product review
			$customer_id = (int) $this->input->post('customer_id');

			//get customer name and email
			$this->db->select('name, email')->from('customers')->where('id_customers', $customer_id);
			$customer_data = $this->db->get()->row();

			$data = array(
				'product_id' => $product_id,
				'review_date' => date('j M Y'),
				'is_registered' => 'yes',
				'customer_id' => $customer_id,
				'name'	=> $customer_data->name,
				'email'	=> $customer_data->email,
				'rating' => $rating,
				'review' => $review
			);

		} else {
			//customer act as a guest during product review
			//get value from serialize form data ajax
			$name = $this->security->xss_clean($this->input->post('name'));
			$email = $this->security->xss_clean($this->input->post('email'));

			$data = array(
				'product_id' => $product_id,
				'review_date' => date('j M Y'),
				'is_registered' => 'no',
				'name'	=> $name,
				'email'	=> $email,
				'rating' => $rating,
				'review' => $review
			);
		}

		$this->db->insert('product_review', $data);

		//get all product reviews
		$this->db->select('*')->from('product_review')->where('product_id', $product_id)->order_by('review_date', 'DESC');
		$data['product_reviews'] = $this->db->get()->result();
		$data['product_id'] = $product_id;

		$this->load->view('ajax/ajax_addproductreview', $data);

	}

	//ajax get price
	public function ajax_get_price() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$id_product_detail = (int) $this->input->post('id_product_details');
		$id_product = (int) $this->input->post('id_product');

		//check if $id_product_detail is exist
		$this->db->select('id_product_details')->from('product_details')->where('id_product_details', $id_product_detail);
		$count_id_product_detail = $this->db->get()->num_rows();

		$this->load->helper('category_discount');
		$category_discount_percentage = category_discount($id_product);

		if(isset($this->session->userdata('customer')['customer_id'])) {

			//customer is logged in
			//check if customer is a reseller. if reseller use reseller min quantity
			$this->db->select('reseller_id')->from('customers')->where('id_customers', $this->session->userdata('customer')['customer_id']);
			$reseller_id = $this->db->get()->row()->reseller_id;

			//check if reseller min quantity already available (already input by admin). If not, display 1 as minimum quantity
			$this->db->select('price')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
			$count_reseller_price = $this->db->get()->num_rows();

			if($reseller_id != NULL && $count_reseller_price > 0) {

				//customer is reseller, and data already inputted by admin. so use reseller price
				$this->db->select('price')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
				$data['price'] = $this->db->get()->row()->price;
				$data['discounted_price'] = 0;

			} elseif($reseller_id != NULL && $id_product_detail == 0) {

				//customer is a reseller. id_product_detail is 0 because he choose no option with 0 id product details
				//get product detail id (for 1st detail only)
				$this->db->select('id_product_details')->from('product_details')->where('product_id', $id_product)->limit(1);
				$id_product_detail = $this->db->get()->row()->id_product_details;

				$this->db->select('price')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
				$data['price'] = $this->db->get()->row()->price;
				$data['discounted_price'] = 0;

			} elseif($reseller_id == NULL || $count_reseller_price == 0) {

				//customer is not a reseller or data not inputted by admin, so use normal price with 0 id product details
				if($category_discount_percentage != NULL) {
				//category discount is active
					if($count_id_product_detail > 0) {

						$this->db->select('price, sku, attributes')->from('product_details')->where('id_product_details', $id_product_detail);
						$prices = $this->db->get()->row();
						$data['price'] = $prices->price;
						$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);

					} else {
						//id_product_details is not available, because customer choose option with 0 id product details
						$this->db->select('price, sku, attributes')->from('product_details')->where('product_id', $id_product)->order_by('id_product_details', 'ASC')->limit(1);
						$prices = $this->db->get()->row();
						$data['price'] = $prices->price;
						$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);
					}

				} else {
					//category discount not active
					if($count_id_product_detail > 0) {

						//get the initial product price from product_details table
						$this->db->select('price, discounted_price')->from('product_details')->where('id_product_details', $id_product_detail)->order_by('id_product_details', 'ASC')->limit(1);
						$prices = $this->db->get()->row();
						$data['price'] = $prices->price;
						$data['discounted_price'] = $prices->discounted_price;

					} else {

						//id_product_details is not available, because customer choose option with 0 id product details
						$this->db->select('price, discounted_price')->from('product_details')->where('product_id', $id_product)->limit(1);
						$prices = $this->db->get()->row();
						$data['price'] = $prices->price;
						$data['discounted_price'] = $prices->discounted_price;
					}
				}
			}

		} else {
			//if customer is not logged in
			if($category_discount_percentage != NULL) {
				//category discount is active
				if($count_id_product_detail > 0) {

					$this->db->select('price, sku, attributes')->from('product_details')->where('id_product_details', $id_product_detail);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;
					$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);

				} else {
					//id_product_details is not available, because customer choose option with 0 id product details
					$this->db->select('price, sku, attributes')->from('product_details')->where('product_id', $id_product)->order_by('id_product_details', 'ASC')->limit(1);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;
					$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);
				}

			} else {
				//category discount not active
				if($count_id_product_detail > 0) {

					//get the initial product price from product_details table
					$this->db->select('price, discounted_price')->from('product_details')->where('id_product_details', $id_product_detail)->order_by('id_product_details', 'ASC')->limit(1);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;
					$data['discounted_price'] = $prices->discounted_price;

				} else {

					//id_product_details is not available, because customer choose option with 0 id product details
					$this->db->select('price, discounted_price')->from('product_details')->where('product_id', $id_product)->limit(1);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;
					$data['discounted_price'] = $prices->discounted_price;
				}
			}
		}

		$this->load->view('ajax/ajax_get_price', $data);
	}

	//ajax get sku. stock, weight
	public function ajax_get_productdetails() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$id_product_detail = (int) $this->input->post('id_product_details');
		$id_product = (int) $this->input->post('id_product');

		//check if $id_product_detail is exist
		$this->db->select('id_product_details')->from('product_details')->where('id_product_details', $id_product_detail);
		$count_id_product_detail = $this->db->get()->num_rows();

		if($count_id_product_detail > 0) {

			$this->db->select('sku, weight, stock')->from('product_details')->where('id_product_details', $id_product_detail);
			$product_details = $this->db->get()->row();
			$data['sku'] = $product_details->sku;
			$data['weight'] = $product_details->weight;
			$data['stock'] = $product_details->stock;

		} else {

			//id_product_details is not available, because customer choose option with 0 id product details
			$this->db->select('sku, weight, stock')->from('product_details')->where('product_id', $id_product)->order_by('id_product_details', 'ASC')->limit(1);
			$product_details = $this->db->get()->row();
			$data['sku'] = $product_details->sku;
			$data['weight'] = $product_details->weight;
			$data['stock'] = $product_details->stock;
		}

		//get product code (SKU), weight, and stock display status
		$this->db->select('show_product_sku, show_product_weight, show_product_stock')->from('configuration')->where('id_configuration', 1);
		$display_status = $this->db->get()->row();
		$data['display_sku'] = $display_status->show_product_sku;
		$data['display_weight'] = $display_status->show_product_weight;
		$data['display_stock'] = $display_status->show_product_stock;

		$this->load->view('ajax/ajax_get_productdetails', $data);
	}

	//ajax get quantity discount
	public function ajax_get_quantity_discount() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$id_product_detail = (int) $this->input->post('id_product_details');
		$id_product = (int) $this->input->post('id_product');

		//GET THE PRICE
		//get initial min quantity
		if(isset($this->session->userdata('customer')['customer_id'])) {

			//customer is logged in
			//check if customer is a reseller. if reseller use reseller min quantity
			$this->db->select('reseller_id')->from('customers')->where('id_customers', $this->session->userdata('customer')['customer_id']);
			$reseller_id = $this->db->get()->row()->reseller_id;

			//check if reseller min quantity already available (already input by admin). If not, display 1 as minimum quantity
			$this->db->select('price')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
			$count_reseller_price = $this->db->get()->num_rows();

			if($reseller_id != NULL && $count_reseller_price > 0) {

				//customer is reseller, and data already inputted by admin. so use reseller price
				$this->db->select('price')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
				$data['price'] = $this->db->get()->row()->price;
				$data['discounted_price'] = 0;

			} elseif($reseller_id != NULL && $id_product_detail == 0) {

				//customer is a reseller. id_product_detail is 0 because he choose no option with 0 id product details
				//get product detail id (for 1st detail only)
				$this->db->select('id_product_details')->from('product_details')->where('product_id', $id_product)->limit(1);
				$id_product_detail = $this->db->get()->row()->id_product_details;

				$this->db->select('price')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
				$data['price'] = $this->db->get()->row()->price;
				$data['discounted_price'] = 0;

			} elseif($reseller_id == NULL || $count_reseller_price == 0) {

				//customer is not a reseller or data already inputted by admin, so use normal price with 0 id product details
				//check if $id_product_detail is exist
				$this->db->select('id_product_details')->from('product_details')->where('id_product_details', $id_product_detail);
				$count_id_products = $this->db->get()->num_rows();

				if($count_id_products > 0) {
					//get the initial product price from product_details table
					$this->db->select('price, discounted_price')->from('product_details')->where('id_product_details', $id_product_detail)->order_by('id_product_details', 'ASC')->limit(1);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;
					$data['discounted_price'] = $prices->discounted_price;
				} else {
					//id_product_details is not available, because customer choose option with 0 id product details
					$this->db->select('price, discounted_price')->from('product_details')->where('product_id', $id_product)->limit(1);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;
					$data['discounted_price'] = $prices->discounted_price;
				}
			}

		} else {
			//if customer is not logged in
			//check if $id_product_detail is exist
			$this->db->select('id_product_details')->from('product_details')->where('id_product_details', $id_product_detail);
			$count_id_products = $this->db->get()->num_rows();

			if($count_id_products > 0) {
				//get the initial product price from product_details table
				$this->db->select('price, discounted_price')->from('product_details')->where('id_product_details', $id_product_detail)->order_by('id_product_details', 'ASC')->limit(1);
				$prices = $this->db->get()->row();
				$data['price'] = $prices->price;
				$data['discounted_price'] = $prices->discounted_price;
			} else {
				//id_product_details is not available, because customer choose option with 0 id product details
				$this->db->select('price, discounted_price')->from('product_details')->where('product_id', $id_product)->limit(1);
				$prices = $this->db->get()->row();
				$data['price'] = $prices->price;
				$data['discounted_price'] = $prices->discounted_price;
			}
		}

		//GET THE QUANTITY
		//check whether quantity_discount_active is no, retail only, reseller only, or both
		$this->db->select('quantity_discount_active')->from('products')->where('id_products', $id_product);
		$quantity_discount_active = $this->db->get()->row()->quantity_discount_active;

		//check quantity discount if exist
		$this->db->select('id_quantity_discount')->from('quantity_discount')->where('product_id', $id_product);
		$count_quantity_discount = $this->db->get()->num_rows();

		if(isset($this->session->userdata('customer')['customer_id'])) {
			//customer is loggedin
			//check if customer is a reseller
			$this->db->select('reseller_id')->from('customers')->where('id_customers', $this->session->userdata('customer')['customer_id']);
			$reseller_id = $this->db->get()->row()->reseller_id;

			if($reseller_id != NULL) {
				//this is a reseller
				//display quantity discount
				if($quantity_discount_active == 'reseller' || $quantity_discount_active == 'retail-reseller') {
					if($count_quantity_discount > 0) {
						//quantity discount exist. get quantity discount
						$this->db->select('*')->from('quantity_discount')->where('product_id', $id_product)->order_by('min_quantity', 'ASC');
						$data['quantity_discount'] = $this->db->get()->result();
					}
				}
			} else {
				//this is a regular customer
				//display quantity discount
				if($quantity_discount_active == 'retail' || $quantity_discount_active == 'retail-reseller') {
					if($count_quantity_discount > 0) {
						//quantity discount exist. get quantity discount
						$this->db->select('*')->from('quantity_discount')->where('product_id', $id_product)->order_by('min_quantity', 'ASC');
						$data['quantity_discount'] = $this->db->get()->result();
					}
				}
			}
		} else {
			//customer is not loggedin
			//display quantity discount
			if($quantity_discount_active == 'retail' || $quantity_discount_active == 'retail-reseller') {
				if($count_quantity_discount > 0) {
					//quantity discount exist. get quantity discount
					$this->db->select('*')->from('quantity_discount')->where('product_id', $id_product)->order_by('min_quantity', 'ASC');
					$data['quantity_discount'] = $this->db->get()->result();
				}
			}
		}

		$this->load->view('ajax/ajax_get_quantity_discount', $data);

	}

	//ajax ajax_get_quantity_discount_price
	public function ajax_get_quantity_discount_price() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$id_product_detail = (int) $this->input->post('id_product_details');
		$id_product = (int) $this->input->post('id_product');
		$quantity = (int) $this->input->post('product_quantity');

		$this->load->helper('category_discount');
		$category_discount_percentage = category_discount($id_product);

		if(isset($this->session->userdata('customer')['customer_id'])) {

			//customer is logged in
			//check if customer is a reseller. if reseller use reseller discounted price
			$this->db->select('reseller_id')->from('customers')->where('id_customers', $this->session->userdata('customer')['customer_id']);
			$reseller_id = $this->db->get()->row()->reseller_id;

			//check if reseller min quantity already available (already input by admin)
			$this->db->select('price')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
			$count_reseller_price = $this->db->get()->num_rows();

			//check if the id_product has quantity discount
    		$this->db->select('id_quantity_discount')->from('quantity_discount')->where('product_id', $id_product);
    		$count_quantity_discount = $this->db->get()->num_rows();

			if($reseller_id != NULL && $count_reseller_price > 0) {

				//customer is reseller, and data already inputted by admin. so use reseller price
				$this->db->select('price')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
				$data['price'] = $this->db->get()->row()->price;

				if ($count_quantity_discount > 0) {

					//count if min_quantity <= '$quantity' is exist
					$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
					$count_discount_percentage = $query->num_rows();

					if($count_discount_percentage > 0) {

						$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
						$discount_percentage = $query->row()->discount_percentage;

						$data['discounted_price'] = $data['price'] - ($data['price'] * $discount_percentage / 100);

					} else {
						$data['discounted_price'] = 0;
					}

				} else {
					$data['discounted_price'] = 0;;
				}

			} elseif($reseller_id != NULL && $id_product_detail == 0) {

				//customer is a reseller. id_product_detail is 0 because he choose no option with 0 id product details
				//get product detail id (for 1st detail only)
				$this->db->select('id_product_details')->from('product_details')->where('product_id', $id_product)->limit(1);
				$id_product_detail = $this->db->get()->row()->id_product_details;

				$this->db->select('price')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
				$data['price'] = $this->db->get()->row()->price;

				if ($count_quantity_discount > 0) {

					//count if min_quantity <= '$quantity' is exist
					$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
					$count_discount_percentage = $query->num_rows();

					if($count_discount_percentage > 0) {

						$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
						$discount_percentage = $query->row()->discount_percentage;

						$data['discounted_price'] = $data['price'] - ($data['price'] * $discount_percentage / 100);

					} else {
						$data['discounted_price'] = 0;
					}

				} else {
					$data['discounted_price'] = 0;;
				}

			} elseif($reseller_id == NULL || $count_reseller_price == 0) {

				//customer is not a reseller or data not yet inputted by admin, so use normal price with 0 id product details
				if($id_product_detail != 0) {
					//product detail is not 0, means customer did choose an option
					//get the initial product price from product_details table
					$this->db->select('price')->from('product_details')->where('id_product_details', $id_product_detail)->order_by('id_product_details', 'ASC')->limit(1);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;

					//check if the id_product has quantity discount
					$this->db->select('id_quantity_discount')->from('quantity_discount')->where('product_id', $id_product);
					$count_quantity_discount = $this->db->get()->num_rows();

					if ($count_quantity_discount > 0) {

						//count if min_quantity <= '$quantity' is exist
						$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
						$count_discount_percentage = $query->num_rows();

						if($count_discount_percentage > 0) {

							$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
							$discount_percentage = $query->row()->discount_percentage;

							$data['discounted_price'] = $data['price'] - ($data['price'] * $discount_percentage / 100);

						} else {
							if($category_discount_percentage != NULL) {
								//category discount is active
								$this->db->select('price, discounted_price')->from('product_details')->where('id_product_details', $id_product_detail);
								$prices = $this->db->get()->row();
								$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);
							} else {
								//category discount is not active
								$this->db->select('discounted_price')->from('product_details')->where('id_product_details', $id_product_detail);
								$data['discounted_price'] = $this->db->get()->row()->discounted_price;
							}
						}

					} else {
						//no quantity discount
						$this->db->select('discounted_price')->from('product_details')->where('id_product_details', $id_product_detail);
						$data['discounted_price'] = $this->db->get()->row()->discounted_price;
					}

				} else {
					//id_product_details is not available, because customer choose option with 0 id product details
					$this->db->select('price')->from('product_details')->where('product_id', $id_product)->limit(1);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;

					//check if the id_product has quantity discount
					$this->db->select('id_quantity_discount')->from('quantity_discount')->where('product_id', $id_product);
					$count_quantity_discount = $this->db->get()->num_rows();

					if ($count_quantity_discount > 0) {

						//count if min_quantity <= '$quantity' is exist
						$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
						$count_discount_percentage = $query->num_rows();

						if($count_discount_percentage > 0) {

							$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
							$discount_percentage = $query->row()->discount_percentage;

							$data['discounted_price'] = $data['price'] - ($data['price'] * $discount_percentage / 100);

						} else {
							if($category_discount_percentage != NULL) {
								//category discount is active
								$this->db->select('price')->from('product_details')->where('product_id', $id_product)->limit(1);
								$prices = $this->db->get()->row();
								$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);
							} else {
								//category discount is not active
								$this->db->select('discounted_price')->from('product_details')->where('product_id', $id_product)->limit(1);
								$data['discounted_price'] = $this->db->get()->row()->discounted_price;
							}
						}

					} else {
						//no quantity discount
						if($category_discount_percentage != NULL) {
							//category discount is active
							$this->db->select('price')->from('product_details')->where('product_id', $id_product)->limit(1);
							$prices = $this->db->get()->row();
							$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);
						} else {
							//category discount is not active
							$this->db->select('discounted_price')->from('product_details')->where('product_id', $id_product)->limit(1);
							$data['discounted_price'] = $this->db->get()->row()->discounted_price;
						}
					}
				}

			}

		} else {

			//if customer is not logged in
			if($id_product_detail != 0) {
				//product detail is not 0, means customer did choose an option
				if($category_discount_percentage != NULL) {
					//category discount is active
					$this->db->select('price')->from('product_details')->where('id_product_details', $id_product_detail);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;
					$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);
				} else {
					//category discount is not active
					//get the initial product price from product_details table
					$this->db->select('price')->from('product_details')->where('id_product_details', $id_product_detail);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;
				}

				//check if the id_product has quantity discount
				$this->db->select('id_quantity_discount')->from('quantity_discount')->where('product_id', $id_product);
				$count_quantity_discount = $this->db->get()->num_rows();

				if ($count_quantity_discount > 0) {

					//count if min_quantity <= '$quantity' is exist
					$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
					$count_discount_percentage = $query->num_rows();

					if($count_discount_percentage > 0) {

						$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
						$discount_percentage = $query->row()->discount_percentage;

						$data['discounted_price'] = $data['price'] - ($data['price'] * $discount_percentage / 100);

					} else {
						if($category_discount_percentage != NULL) {
							//category discount is active
							$this->db->select('price')->from('product_details')->where('id_product_details', $id_product_detail);
							$prices = $this->db->get()->row();
							$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);
						} else {
							//category discount is not active
							$this->db->select('discounted_price')->from('product_details')->where('id_product_details', $id_product_detail);
							$data['discounted_price'] = $this->db->get()->row()->discounted_price;
						}
					}

				} else {
					//no quantity discount
					$this->db->select('discounted_price')->from('product_details')->where('id_product_details', $id_product_detail);
					$data['discounted_price'] = $this->db->get()->row()->discounted_price;
				}

			} else {
				//id_product_details is not available, because customer choose option with 0 id product details
				if($category_discount_percentage != NULL) {
					//category discount is active
					$this->db->select('price')->from('product_details')->where('product_id', $id_product)->limit(1);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;
					$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);
				} else {
					//category discount is not active
					$this->db->select('price')->from('product_details')->where('product_id', $id_product)->limit(1);
					$prices = $this->db->get()->row();
					$data['price'] = $prices->price;
				}

				//check if the id_product has quantity discount
				$this->db->select('id_quantity_discount')->from('quantity_discount')->where('product_id', $id_product);
				$count_quantity_discount = $this->db->get()->num_rows();

				if ($count_quantity_discount > 0) {

					//count if min_quantity <= '$quantity' is exist
					$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
					$count_discount_percentage = $query->num_rows();

					if($count_discount_percentage > 0) {

						$query = $this->db->query("SELECT discount_percentage FROM quantity_discount WHERE product_id = '$id_product' AND  min_quantity <= '$quantity' ORDER BY ABS(min_quantity - '$quantity') LIMIT 1");
						$discount_percentage = $query->row()->discount_percentage;

						$data['discounted_price'] = $data['price'] - ($data['price'] * $discount_percentage / 100);

					} else {
						if($category_discount_percentage != NULL) {
							//category discount is active
							$this->db->select('price')->from('product_details')->where('product_id', $id_product)->limit(1);
							$prices = $this->db->get()->row();
							$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);
						} else {
							//category discount is not active
							$this->db->select('discounted_price')->from('product_details')->where('product_id', $id_product)->limit(1);
							$data['discounted_price'] = $this->db->get()->row()->discounted_price;
						}
					}

				} else {
					//no quantity discount
					if($category_discount_percentage != NULL) {
						//category discount is active
						$this->db->select('price')->from('product_details')->where('product_id', $id_product)->limit(1);
						$prices = $this->db->get()->row();
						$data['discounted_price'] = $prices->price - ($prices->price * $category_discount_percentage/100);
					} else {
						//category discount is not active
						$this->db->select('discounted_price')->from('product_details')->where('product_id', $id_product)->limit(1);
						$data['discounted_price'] = $this->db->get()->row()->discounted_price;
					}
				}
			}
		}

		$this->load->view('ajax/ajax_get_price', $data);

	}

	//ajax get product purchase min quantity
	public function ajax_get_min_quantity() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$id_product_detail = (int) $this->input->post('id_product_details');
		$id_product = (int) $this->input->post('id_product');

		//get initial min quantity
		if(isset($this->session->userdata('customer')['customer_id'])) {

			//customer is logged in
			//check if customer is a reseller. if reseller use reseller min quantity
			$this->db->select('reseller_id')->from('customers')->where('id_customers', $this->session->userdata('customer')['customer_id']);
			$reseller_id = $this->db->get()->row()->reseller_id;

			//check if reseller min quantity already available (already input by admin). If not, display 1 as minimum quantity
			$this->db->select('min_quantity')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
			$count_reseller = $this->db->get()->num_rows();

			if($reseller_id != NULL && $count_reseller > 0) {

				//customer is reseller, and data already inputtedby admin. so use reseller min quantity
				$this->db->select('min_quantity')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
				$data['reseller_min_quantity'] = $this->db->get()->row()->min_quantity;

			} elseif($reseller_id == NULL) {

				$data['reseller_min_quantity'] = 1;

			} elseif($reseller_id != NULL && $count_reseller == 0) {

				//customer is a reseller, but data not input yet, or customer choose empty option..
				//then give default reseller min quantity
				//get id_product_details
				$this->db->select('id_product_details')->from('product_details')->where('product_id', $id_product)->order_by('id_product_details', 'ASC')->limit(1);
				$id_default_product_detail = $this->db->get()->row()->id_product_details;

				//get default reseller min quantity
				$this->db->select('min_quantity')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_default_product_detail);
				$data['reseller_min_quantity'] = $this->db->get()->row()->min_quantity;
			}

		} else {
			//if customer is not logged in
			//set min quantity as 1
			$data['reseller_min_quantity'] = 1;
		}

		echo $data['reseller_min_quantity'];
	}

	//ajax get product purchase min quantity
	public function ajax_get_quantity_options() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$id_product_detail = (int) $this->input->post('id_product_details');
		$id_product = (int) $this->input->post('id_product');

		//get min quantity
		if(isset($this->session->userdata('customer')['customer_id'])) {

			//customer is logged in
			//check if customer is a reseller. if reseller use reseller min quantity
			$this->db->select('reseller_id')->from('customers')->where('id_customers', $this->session->userdata('customer')['customer_id']);
			$reseller_id = $this->db->get()->row()->reseller_id;

			//check if reseller min quantity already available (already input by admin). If not, display 1 as minimum quantity
			$this->db->select('min_quantity')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
			$count_reseller = $this->db->get()->num_rows();

			if($reseller_id != NULL && $count_reseller > 0) {

				//customer is reseller, and data already inputtedby admin. so use reseller min quantity
				$this->db->select('min_quantity')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_product_detail);
				$data['reseller_min_quantity'] = $this->db->get()->row()->min_quantity;

			} elseif($reseller_id == NULL) {

				$data['reseller_min_quantity'] = 1;

			} elseif($reseller_id != NULL && $count_reseller == 0) {

				//customer is a reseller, but data not input yet, or customer choose empty option..
				//then give default reseller min quantity
				//get id_product_details
				$this->db->select('id_product_details')->from('product_details')->where('product_id', $id_product)->order_by('id_product_details', 'ASC')->limit(1);
				$id_default_product_detail = $this->db->get()->row()->id_product_details;

				//get default reseller min quantity
				$this->db->select('min_quantity')->from('resellers_price')->where('reseller_id', $reseller_id)->where('product_detail_id', $id_default_product_detail);
				$data['reseller_min_quantity'] = $this->db->get()->row()->min_quantity;
			}

		} else {
			//if customer is not logged in
			//set min quantity as 1
			$data['reseller_min_quantity'] = 1;
		}

		$this->load->view('ajax/ajax_get_quantity_options', $data);
	}


	//ajax product page add to cart
	public function ajax_add_to_cart() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$this->load->library('cart');

		$data['id'] = (int) $this->input->post('product_id');
		$data['qty'] = (int) $this->input->post('qty');

		$this->db->select('is_backorder');
		$this->db->from('products');
		$this->db->where('id_products', $data['id']);
		$myproduct = $this->db->get()->row();

		if($myproduct->is_backorder == 'no'){
			$query = $this->db->query("SELECT sum(stock) FROM `stock` WHERE `id_product` = '".(int) $data['id']."'");
			$stock = $query->result_array();
			if($stock[0]['sum(stock)'] < $data['qty']){
				echo 'false';
                $data = [
                    'id_product' => $data['id']
                ];
                $this->db->insert('stock_log', $data);
				exit();
			}
		}

		if($data['qty'] == '0'){
			echo 'farmaku';
			exit();
		}

		//get product data
		$this->db->select('product_code, title, competitor_price, sale_price, discounted_price, is_sale')->from('products')->where('id_products', (int) $data['id']);
		$product = $this->db->get()->row();
		/* $data['name'] = ucwords($product->title) . '<br>Ref:' . $product->product_code; */
		$data['name'] = ucwords($product->title);

		//evaluate final price
		//check for flash sale
		if($this->session->has_userdata('flashsale_id_active')) {
			//if flash sale session is currently active
			$this->db->select('product_id, discounted_price')->from('flashsale_products')->where('flashsale_id',$this->session->userdata('flashsale_id_active'))->where('product_id',$data['id']);
			$flashsale_product = $this->db->get()->row();
		}
		if(count($flashsale_product) > 0) {
			$data['price'] = $flashsale_product->discounted_price;
		} else {
           if($product->is_sale == 'no') {
          	 $data['price'] = $product->sale_price;
           } else {
           	 $data['price'] = $product->discounted_price;
           }
		}

		$data['options']['warehouse_name'] = '';
		$data['options']['warehouse_id'] = NULL;
		$this->cart->product_name_rules = '[:print:]'; //this is to eliminate cart product name restriction on special characters
		$this->cart->insert($data);

		$this->load->helper('cart');

		$this->load->view('ajax/ajax_add_to_cart');
	}

	//callback function validation cek stock available when add to cart
	public function cek_stock() {

		$id_product_details = (int) $this->input->post('product_size');
		$chosen_quantity = (int) $this->input->post('qty');

		//get current stock froms product_details table
		$this->db->select('stock');
		$this->db->from('product_details');
		$this->db->where('id_product_details', $id_product_details);
		$query = $this->db->get();
		$current_stock = (int) $query->row()->stock;

		//check if quantity is less or equal to current stock
		if ($chosen_quantity > $current_stock) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function ajax_get_district() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$province_id = (int) $this->input->post('id_province');

		//check districts table if province_id already available
		$this->db->select('id_indonesia_districts')->from('indonesia_districts')->where('indonesia_id_province', $province_id);
		$count_districts = $this->db->get()->num_rows();

		if($count_districts > 0) {

			//districts already available, get the districts
			$this->db->select('rajaongkir_id_district, district')->from('indonesia_districts')->where('indonesia_id_province', $province_id);
			$data['districts'] = $this->db->get()->result();

		} else {

			//districts not available yet..then get rajaongkir data and store into districts table
			$this->load->helper('rajaongkir');
			//get list of districts from RajaOngkir.com API
			$districts = get_rajaongkir_data('city?province=' . $province_id); //get from helper file

			foreach($districts['rajaongkir']['results'] as $district) {

				//check first if rajaongkir district_id already exist..
				$this->db->select('rajaongkir_id_district')->from('indonesia_districts')->where('rajaongkir_id_district', $district['city_id']);
				$count_districts = $this->db->get()->num_rows();

				if($count_districts == 0) {
					//can input new data, because still empty
					//insert into districts database
					$data = array(
						'rajaongkir_id_district' => $district['city_id'],
						'district' => $district['city_name'],
						'indonesia_id_province' => $province_id
					);
					$this->db->insert('indonesia_districts', $data);
				}
			}

			//districts should be available now, get the districts
			$this->db->select('rajaongkir_id_district, district')->from('indonesia_districts')->where('indonesia_id_province', $province_id);
			$data['districts'] = $this->db->get()->result();
		}

		$this->load->view('ajax/ajax_get_district', $data);
	}

	public function ajax_get_shipping_district() {

		//if(!$_POST) { show_404(); }

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$shipping_province_id = (int) $this->input->post('id_shipping_province');

		//check districts table if province_id already available
		$this->db->select('id_indonesia_districts')->from('indonesia_districts')->where('indonesia_id_province', $shipping_province_id);
		$count_districts = $this->db->get()->num_rows();

		if($count_districts > 0) {

			//districts already available, get the districts
			$this->db->select('rajaongkir_id_district, district')->from('indonesia_districts')->where('indonesia_id_province', $shipping_province_id);
			$data['shipping_districts'] = $this->db->get()->result();

		} else {
			//districts not available yet..then get rajaongkir data and store into districts table
			$this->load->helper('rajaongkir');
			//get list of districts from RajaOngkir.com API
			$districts = get_rajaongkir_data('city?province=' . $shipping_province_id); //get from helper file

			foreach($districts['rajaongkir']['results'] as $district) {

				//check first if rajaongkir district_id already exist..
				$this->db->select('rajaongkir_id_district')->from('indonesia_districts')->where('rajaongkir_id_district', $district['city_id']);
				$count_districts = $this->db->get()->num_rows();

				if($count_districts == 0) {
					//can input new data, because still empty
					//insert into districts database
					$data = array(
						'rajaongkir_id_district' => $district['city_id'],
						'district' => $district['city_name'],
						'indonesia_id_province' => $shipping_province_id
					);
					$this->db->insert('indonesia_districts', $data);
				}
			}

			//districts should be available now, get the districts
			$this->db->select('rajaongkir_id_district, district')->from('indonesia_districts')->where('indonesia_id_province', $shipping_province_id);
			$data['shipping_districts'] = $this->db->get()->result();
		}

		$datacustomer_province = array(
			'shipping_id_province' => $shipping_province_id,
			'id_province' => $shipping_province_id
		);

		$this->db->where('id_customers', (int) $this->session->userdata('customer')['customer_id']);
		$this->db->update('customers', $datacustomer_province);
		$this->load->view('ajax/ajax_get_shipping_district', $data);
	}

	public function ajax_get_subdistrict() {

		//if(!$_POST) { show_404(); }

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$district_id = (int) $this->input->post('id_district');

		//check subdistricts table if district_id already available
		$this->db->select('id_indonesia_subdistricts')->from('indonesia_subdistricts')->where('indonesia_id_district', $district_id);
		$count_subdistricts = $this->db->get()->num_rows();

		if($count_subdistricts > 0) {

			//subdistricts already available, get the subdistricts
			$this->db->select('rajaongkir_id_subdistrict, subdistrict')->from('indonesia_subdistricts')->where('indonesia_id_district', $district_id);
			$data['subdistricts'] = $this->db->get()->result();

		} else {
			//subdistricts not available yet..then get rajaongkir data and store into subdistricts table
			$this->load->helper('rajaongkir');
			//get list of subdistricts from RajaOngkir.com API
			$subdistricts = get_rajaongkir_data('subdistrict?city=' . $district_id); //get from helper file

			foreach($subdistricts['rajaongkir']['results'] as $subdistrict) {

				//check first if rajaongkir subdistrict_id already exist..
				$this->db->select('rajaongkir_id_subdistrict')->from('indonesia_subdistricts')->where('rajaongkir_id_subdistrict', $subdistrict['subdistrict_id']);
				$count_subdistricts = $this->db->get()->num_rows();

				if($count_subdistricts == 0) {
					//can input new data, because still empty
					//insert into subdistricts database
					$data = array(
						'rajaongkir_id_subdistrict' => $subdistrict['subdistrict_id'],
						'subdistrict' => $subdistrict['subdistrict_name'],
						'indonesia_id_district' => $district_id
					);
					$this->db->insert('indonesia_subdistricts', $data);
				}
			}

			//subdistricts should be available now, get the subdistricts
			$this->db->select('rajaongkir_id_subdistrict, subdistrict')->from('indonesia_subdistricts')->where('indonesia_id_district', $district_id);
			$data['subdistricts'] = $this->db->get()->result();
		}

		$this->load->view('ajax/ajax_get_subdistrict', $data);
	}

	public function ajax_get_shipping_subdistrict() {

		//if(!$_POST) { show_404(); }

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$shipping_district_id = (int) $this->input->post('id_shipping_district');

		//check subdistricts table if district_id already available
		$this->db->select('id_indonesia_subdistricts')->from('indonesia_subdistricts')->where('indonesia_id_district', $shipping_district_id);
		$count_subdistricts = $this->db->get()->num_rows();

		if($count_subdistricts > 0) {

			//subdistricts already available, get the subdistricts
			$this->db->select('rajaongkir_id_subdistrict, subdistrict')->from('indonesia_subdistricts')->where('indonesia_id_district', $shipping_district_id);
			$data['shipping_subdistricts'] = $this->db->get()->result();

		} else {
			//subdistricts not available yet..then get rajaongkir data and store into subdistricts table
			$this->load->helper('rajaongkir');
			//get list of subdistricts from RajaOngkir.com API
			$subdistricts = get_rajaongkir_data('subdistrict?city=' . $shipping_district_id); //get from helper file

			foreach($subdistricts['rajaongkir']['results'] as $subdistrict) {

				//check first if rajaongkir subdistrict_id already exist..
				$this->db->select('rajaongkir_id_subdistrict')->from('indonesia_subdistricts')->where('rajaongkir_id_subdistrict', $subdistrict['subdistrict_id']);
				$count_subdistricts = $this->db->get()->num_rows();

				if($count_subdistricts == 0) {
					//can input new data, because still empty
					//insert into subdistricts database
					$data = array(
						'rajaongkir_id_subdistrict' => $subdistrict['subdistrict_id'],
						'subdistrict' => $subdistrict['subdistrict_name'],
						'indonesia_id_district' => $shipping_district_id
					);
					$this->db->insert('indonesia_subdistricts', $data);
				}
			}

			//subdistricts should be available now, get the subdistricts
			$this->db->select('rajaongkir_id_subdistrict, subdistrict')->from('indonesia_subdistricts')->where('indonesia_id_district', $shipping_district_id);
			$data['shipping_subdistricts'] = $this->db->get()->result();
		}

		$datacustomer_district = array(
			'shipping_id_district' => $shipping_district_id,
			'id_district' => $shipping_district_id
		);

		$this->db->where('id_customers', (int) $this->session->userdata('customer')['customer_id']);
		$this->db->update('customers', $datacustomer_district);

		$this->load->view('ajax/ajax_get_shipping_subdistrict', $data);
	}

	public function verifikasikoderegister(){
		$nohp = $this->security->xss_clean($this->input->post('handphone_number'));

		$random1 = rand(1,9);
		$random2 = rand(1,9);
		$random3 = rand(1,9);
		$random4 = rand(1,9);
		$sms_code = $random1 . $random2 . $random3 . $random4;

		$this->db->select('id_sms_code')->from('sms_code')->where('phone', $nohp);
		$count_handphone_number = $this->db->get()->num_rows();
		if($count_handphone_number == 0) {
			//handphone number not exist yet...then add new record
			$data = array(
				'phone' => $nohp,
				'sms_code' => $sms_code,
				'status' => '0'
			);
			$this->db->insert('sms_code', $data);

		} else {
			//handphone number already exist...then update record
			$data = array(
				'sms_code' => $sms_code,
				'status' => '0'
			);
			$this->db->where('phone', $nohp);
			$this->db->update('sms_code', $data);
		}

		$database = $this->db->select('*')->from('sms')->where('code', 'VerifikasKodeRegister')->get()->row();
		$pesan = $database->message;
		$desc = $database->desc;
		$url = $database->url;

		if($_POST){
			$check = substr($nohp,0,1);
			if($check == '+'){
				$nohp = str_replace('+','',$nohp);
			}

			if($check == '0'){
				$out = ltrim($nohp, "0");
				$nohp = '62'.$out;
			}

			if($check == '8'){
				$nohp = '62'.$nohp;
			}

			// $url = 'http://api-sms.nadyne.com/sms.php';
			$pesan = str_replace('-','%0a',$pesan);
			$pesan = str_replace(" ","+",$pesan);
			$pesan = str_replace("[code]",$sms_code,$pesan);
			$desc = str_replace(" ","+",$desc);
			$dataArray = array(
				'user' => $database->user,
				'pwd' => $database->pwd,
				'sender' => $database->sender,
				'msisdn' => $nohp,
				'message' => $pesan,
				'desc' => $desc
			);

			$ch = curl_init();
			$data = http_build_query($dataArray);

			$getUrl = $url.'?user='.$database->user.'&pwd='.$database->pwd.'&sender='.$database->sender.'&msisdn='.$nohp.'&message='.$pesan.'&desc='.$desc;
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $getUrl);
			curl_setopt($ch, CURLOPT_TIMEOUT, 80);

			$response = curl_exec($ch);

			if(curl_error($ch)){
				echo 'Request Error:' . curl_error($ch);
			}
			else
			{
				if (strpos($response, 'FAILED') == TRUE) {
					echo 'false';
					exit();
				} else {
					echo $getUrl;
					exit();
				}
			}

			curl_close($ch);
		} else {
			return redirect('/');
			exit();
		}
	}

	public function verifikasikodeupdatehp(){
		$nohp = $this->security->xss_clean($this->input->post('handphone_number'));

		$this->db->select('*')->from('customers')->where('phone', $nohp);
		$checkno = $this->db->get()->num_rows();

		if($checkno > 0){
			echo 'detected';
			exit();
		}

		$random1 = rand(1,9);
		$random2 = rand(1,9);
		$random3 = rand(1,9);
		$random4 = rand(1,9);
		$sms_code = $random1 . $random2 . $random3 . $random4;

		$this->db->select('id_sms_code')->from('sms_code')->where('phone', $nohp);
		$count_handphone_number = $this->db->get()->num_rows();
		if($count_handphone_number == 0) {
			//handphone number not exist yet...then add new record
			$data = array(
				'phone' => $nohp,
				'sms_code' => $sms_code,
				'status' => '0'
			);
			$this->db->insert('sms_code', $data);

		} else {
			//handphone number already exist...then update record
			$data = array(
				'sms_code' => $sms_code,
				'status' => '0'
			);
			$this->db->where('phone', $nohp);
			$this->db->update('sms_code', $data);
		}

		$database = $this->db->select('*')->from('sms')->where('code', 'VerifikasiKodeUpdateHP')->get()->row();
		$pesan = $database->message;
		$desc = $database->desc;
		$url = $database->url;

		if($_POST){
			$check = substr($nohp,0,1);
			if($check == '+'){
				$nohp = str_replace('+','',$nohp);
			}

			if($check == '0'){
				$out = ltrim($nohp, "0");
				$nohp = '62'.$out;
			}

			if($check == '8'){
				$nohp = '62'.$nohp;
			}

			// $url = 'http://api-sms.nadyne.com/sms.php';
			$pesan = str_replace('-','%0a',$pesan);
			$pesan = str_replace(" ","+",$pesan);
			$pesan = str_replace("[code]",$sms_code,$pesan);
			$desc = str_replace(" ","+",$desc);
			$dataArray = array(
				'user' => $database->user,
				'pwd' => $database->pwd,
				'sender' => $database->sender,
				'msisdn' => $nohp,
				'message' => $pesan,
				'desc' => $desc
			);

			$ch = curl_init();
			$data = http_build_query($dataArray);

			$getUrl = $url.'?user='.$database->user.'&pwd='.$database->pwd.'&sender='.$database->sender.'&msisdn='.$nohp.'&message='.$pesan.'&desc='.$desc;
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $getUrl);
			curl_setopt($ch, CURLOPT_TIMEOUT, 80);

			$response = curl_exec($ch);

			if(curl_error($ch)){
				echo 'Request Error:' . curl_error($ch);
			}
			else
			{
				if (strpos($response, 'FAILED') == TRUE) {
					echo 'false';
					exit();
				} else {
					echo 'success';
					exit();
				}
			}

			curl_close($ch);
		} else {
			return redirect('/');
			exit();
		}
	}

	public function send_sms_code(){
		$nohp = $this->security->xss_clean($this->input->post('handphone_number'));

		$random1 = rand(1,9);
		$random2 = rand(1,9);
		$random3 = rand(1,9);
		$random4 = rand(1,9);
		$sms_code = $random1 . $random2 . $random3 . $random4;

		$this->db->select('id_sms_code')->from('sms_code')->where('phone', $nohp);
		$count_handphone_number = $this->db->get()->num_rows();
		if($count_handphone_number == 0) {
			//handphone number not exist yet...then add new record
			$data = array(
				'phone' => $nohp,
				'sms_code' => $sms_code,
				'status' => '0'
			);
			$this->db->insert('sms_code', $data);

		} else {
			//handphone number already exist...then update record
			$data = array(
				'sms_code' => $sms_code,
				'status' => '0'
			);
			$this->db->where('phone', $nohp);
			$this->db->update('sms_code', $data);
		}

		$database = $this->db->select('*')->from('sms')->where('code', 'LoginSMS')->get()->row();
		$pesan = $database->message;
		$desc = $database->desc;
		$url = $database->url;

		if($_POST){
			$check = substr($nohp,0,1);
			if($check == '+'){
				$nohp = str_replace('+','',$nohp);
			}

			if($check == '0'){
				$out = ltrim($nohp, "0");
				$nohp = '62'.$out;
			}

			if($check == '8'){
				$nohp = '62'.$nohp;
			}

			// $url = 'http://api-sms.nadyne.com/sms.php';
			$pesan = str_replace('-','%0a',$pesan);
			$pesan = str_replace(" ","+",$pesan);
			$pesan = str_replace("[code]",$sms_code,$pesan);
			$desc = str_replace(" ","+",$desc);
			$dataArray = array(
				'user' => $database->user,
				'pwd' => $database->pwd,
				'sender' => $database->sender,
				'msisdn' => $nohp,
				'message' => $pesan,
				'desc' => $desc
			);

			$ch = curl_init();
			$data = http_build_query($dataArray);

			$getUrl = $url.'?user='.$database->user.'&pwd='.$database->pwd.'&sender='.$database->sender.'&msisdn='.$nohp.'&message='.$pesan.'&desc='.$desc;
			//echo file_get_contents($url.'?user='.$database->user.'&pwd='.$database->pwd.'&sender='.$database->sender.'&msisdn='.$nohp.'&message='.$pesan.'&desc='.$desc);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $getUrl);
			curl_setopt($ch, CURLOPT_TIMEOUT, 80);

			$response = curl_exec($ch);

			if(curl_error($ch)){
				echo 'Request Error:' . curl_error($ch);
			}
			else
			{
				if (strpos($response, 'FAILED') == TRUE) {
					echo 'false';
					$now = date('Y-m-d H:i:s');
					$data = array(
						'payload_receive'	=> $response,
						'response_send'    	=> $getUrl,
						'ip_sender'			=> $this->input->ip_address(),
						'date_log'    		=> $now
					);
					$this->db->insert('api_log', $data);
					$last_id = $this->db->insert_id();
					
					$this->db->select('logo, from_email, website_name, email_smtp_host, email_smtp_port, email_smtp_password, email_smtp')->from('configuration')->where('id_configuration', 1);
					$website_data = $this->db->get()->row();
					
					$sendTo = array('it@farmaku.com');
					$this->load->library('email');
					//get email setting
					$config['protocol'] = 'smtp';
					$config['smtp_host'] = $website_data->email_smtp_host;
					$config['smtp_port'] = $website_data->email_smtp_port;
					$config['smtp_user'] = $website_data->email_smtp;
					$config['smtp_pass'] = $website_data->email_smtp_password;
					$config['mailtype'] = 'html';
					$config['charset'] = 'iso-8859-1';
					$config['wordwrap'] = TRUE;
					$config['newline'] = "\r\n"; //use double quotes to comply with RFC 822 standard
					$this->email->clear(true);
					$this->email->initialize($config);
					$this->email->from('report@farmaku.com');
					$this->email->to($sendTo);
					$this->email->subject('Report SMS Failed');
					$this->email->message($response."<br>Cek table api_log id: ".$last_id );
					$this->email->send();
					exit();
				} else {
					echo 'send';
					exit();
				}
			}

			curl_close($ch);
		} else {
			return redirect('/');
			exit();
		}
	}

	/*public function send_sms_code() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$phone = $this->security->xss_clean($this->input->post('handphone_number'));

		//create 4 random number SMS Code
		$random1 = rand(1,9);
		$random2 = rand(1,9);
		$random3 = rand(1,9);
		$random4 = rand(1,9);
		$sms_code = $random1 . $random2 . $random3 . $random4;

		//check if the phone number already exist in sms_code table
		$this->db->select('id_sms_code')->from('sms_code')->where('phone', $phone);
		$count_handphone_number = $this->db->get()->num_rows();

		if($count_handphone_number == 0) {
			//handphone number not exist yet...then add new record
			$data = array(
				'phone' => $phone,
				'sms_code' => $sms_code
			);
			$this->db->insert('sms_code', $data);

		} else {
			//handphone number already exist...then update record
			$data = array(
				'sms_code' => $sms_code
			);
			$this->db->where('phone', $phone);
			$this->db->update('sms_code', $data);
		}

		//send sms code to user's phone by sms gateway..
		$url = 'http://gateway.siskomdigital.com:12010/cgi-bin/sendsms';
		$params = array( 'gw-username' => 'oky18003', 'gw-password' => '1qa2ws4r', 'gw-to' => '62' . $phone, 'gw-from' => 'Farmaku.com', 'gw-text' => 'Farmaku.com OTP Anda adalah ' . $sms_code,
		'gw-coding' => '1', 'gw-dlr-url' => base_url() . 'sms_receiver',
		'gw-dlr-mask' => '1'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close ($ch);
		echo $response; //contoh: status=0&msgid=0028_alpha0219164522660005.0001;

		//update status and msgid into sms_code table
		$response_array = explode('&', $response);
		$status_array = explode('=', $response_array[0]);
		$status = $status_array[1];

		$msgid_array = explode('=', $response_array[1]);
		$msgid = $msgid_array[1];

		//update record
		$data = array(
			'status' => $status,
			'msgid' => $msgid
		);
		$this->db->where('phone', $phone);
		$this->db->update('sms_code', $data);

		echo $response;

	}*/

	public function ajax_check_stock() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$qty = (int) $this->security->xss_clean($this->input->post('qty'));
		$product_id = (int) $this->security->xss_clean($this->input->post('product_id'));
		$cart_row_id = $this->security->xss_clean($this->input->post('row_id'));
		$subtotal = $this->security->xss_clean($this->input->post('subtotal'));

		//update qty to cart item
		$data = array(
	        'rowid' => $cart_row_id,
	        'qty'   => $qty,
	        'subtotal' => $subtotal
		);
		$this->cart->update($data);
		$this->benchmark->mark('code_start');	
		//get backorder status
		$this->db->select('is_backorder')->from('products')->where('id_products', $product_id);
		$is_backorder = $this->db->get()->row()->is_backorder;
		$this->benchmark->mark('code_end');

		//get total stok from warehouse
		$this->db->select_sum('stock')->from('stock')->where('id_product', $product_id);
		$total_stock = $this->db->get()->row()->stock;

		if($total_stock < $qty) {
			if($is_backorder == 'no') {
                echo 'stok tidak cukup';
			}
		} else {
			//stock cukup..
			echo '&nbsp;';
		}
	}

	public function ajax_set_subtotal() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$qty 			= (int) $this->security->xss_clean($this->input->post('qty'));
		$product_id 	= (int) $this->security->xss_clean($this->input->post('product_id'));
		$cart_row_id 	= $this->security->xss_clean($this->input->post('row_id'));
		$price 			= $this->security->xss_clean($this->input->post('price'));
		$subtotal 		= $qty*$price;

		$data = array(
	        'rowid' => $cart_row_id,
	        'qty'   => $qty,
	        'subtotal' => $subtotal
		);
		$this->cart->update($data);

		$cart = $this->cart->contents();
		$grand_total = 0;
        $count_grand_total = 0;
		foreach ($cart as $item) {
			$count_grand_total = $count_grand_total + $item['subtotal'];
		}
		if($count_grand_total > 0){
			$grand_total = $count_grand_total;
		}

		$data['subtotal'] 	= 'IDR ' .  number_format($subtotal);
		$data['grand_total']= 'TOTAL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IDR '. number_format($grand_total);
	
		// Data Layer
		$data['datalayer'] = array(
			'event' => 'EEcheckout'
		);
		$data['datalayer']['ecommerce']['checkout']['actionField'] = array(
			"step" => 1
		);
		$data['datalayer']['ecommerce']['checkout']['products'] = array();
		$i=1;
		foreach ($this->cart->contents() as $items){
			$this->db->select(" products.title, products.product_code AS code, brands.brand AS brnd, cp.category AS cat,  products.alias as aliases , case when is_sale = 'yes' then discounted_price else sale_price end as price 
			from products
			join brands on brands.id_brands = products.brand_id
			left JOIN(
			SELECT * FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			and categories.parent IS NULL
			AND categories.id_categories IN(364,389,410,440,489,500,525,547)
			) cp ON cp.id_product = products.id_products 
			where products.id_products = ".$items['id']."
			GROUP BY products.id_products
							   ");
			$sub = $this->db->get()->row();
			
			$query = "";
			$query .= "(SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
			and categories.id_categories IN(364,389,410,440,489,500,525,547) )";
		
			$query .= "UNION all";
	
			$query .= "(
			SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
				and categories.parent IN(364,389,410,440,489,500,525,547) 
			)";
		
			$query .= "UNION all";
	
			$query .= "(SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
			and categories.parent IN(
				SELECT id_categories 
				FROM categories 
				where categories.parent IN(364,389,410,440,489,500,525,547)
			) )"; 
	
		
			$cat = $this->db->query($query)->result();
			
			$ecomm_id_arr["id"] = $sub->code;
			$ecomm_id_arr["name"] = $items["name"];
			$ecomm_id_arr["price"] = $items["subtotal"];
			$ecomm_id_arr["brand"] = $sub->brnd;
			$ecomm_id_arr["category"] = $cat[0]->category.' - '.$cat[1]->category.' - '.$cat[2]->category;
			$ecomm_id_arr["position"] = $i;
			$ecomm_id_arr["quantity"] = (int)$items["qty"];
			array_push($data['datalayer']['ecommerce']['checkout']['products'], $ecomm_id_arr);
			$i++;
		}
		echo json_encode($data);
	}

	public function ajax_get_2hourdelivery() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		//get current subdistricts for 2hour and 1day delivery
		$this->db->select('twohour_subdistrict_id')->from('shipment_method_express')->where('warehouse_id', $this->input->post('id_warehouse'));
		$data['current_2hour_subdistrict_id'] = $this->db->get()->result();

		$this->db->select('*')->from('indonesia_subdistricts');
        $this->db->join('indonesia_districts', 'indonesia_districts.rajaongkir_id_district = indonesia_subdistricts.indonesia_id_district');
        $this->db->where('indonesia_districts.indonesia_id_province', $this->input->post('id_province'));
        $data['subdistricts'] = $this->db->get()->result();

        $this->load->view('ajax/ajax_2hourdelivery', $data);
	}

	public function ajax_get_1dayservice() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$this->db->select('oneday_subdistrict_id')->from('shipment_method_express')->where('warehouse_id', $this->input->post('id_warehouse'));
		$data['current_1day_subdistrict_id'] = $this->db->get()->result();

		$this->db->select('*')->from('indonesia_subdistricts');
        $this->db->join('indonesia_districts', 'indonesia_districts.rajaongkir_id_district = indonesia_subdistricts.indonesia_id_district');
        $this->db->where('indonesia_districts.indonesia_id_province', $this->input->post('id_province'));
        $data['subdistricts'] = $this->db->get()->result();

        $this->load->view('ajax/ajax_1dayservice', $data);
	}

	public function ajax_check_stock_shipping() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$qty = (int) $this->security->xss_clean($this->input->post('qty'));
		
		$product_id = (int) $this->security->xss_clean($this->input->post('product_id'));
		
		$warehouse_id = $this->security->xss_clean($this->input->post('warehouse_id'));
		
		$is_backorder = $this->security->xss_clean($this->input->post('is_backorder'));

		//get total stok from warehouse
		error_reporting(0);
		$this->db->select('stock')->from('stock')->where('id_product', $product_id)->where('warehouse_id', $warehouse_id);
		$warehouse_stock = $this->db->get()->row()->stock;

		if($warehouse_stock < $qty) {
			if($is_backorder == 'no') {
				$data = array(
                    'id_product' => $product_id				  
                );
                $this->db->insert('stock_log', $data);
				echo 'stok tidak cukup';
			}
		} else {
			//stock cukup..
			echo '&nbsp;';
		}
	}

	public function ajax_change_shipping_fee() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$this->load->helper('shipping');
		$this->load->helper('rajaongkir');

	    $data['qty'] = $this->input->post('qty');
	    $data['price'] = $this->input->post('price');
	    $data['rowid'] = $this->input->post('rowid');
	    $data['warehouse_id'] = $this->input->post('warehouse_id');
	    $data['shipping_method_ids'] = $this->input->post('shipping_method_ids');
	    $data['shipping_id_subdistrict'] = $this->input->post('shipping_id_subdistrict');
	    $data['product_id'] = $this->input->post('product_id');
	    $data['selected_shipping_method_id'] = $this->input->post('selected_shipping_method_id');
	    $data['shipping_express'] = $this->input->post('shipping_express');

	    //get shipping fee
	    if ( $data['selected_shipping_method_id'] == 8 || $data['selected_shipping_method_id'] == 9 ) {
	    	$shipping_info = getAPI($data['selected_shipping_method_id'],$this->session->userdata('guest_shipping_id')['pinpoin'],$this->session->userdata('customer')['customer_id'],$data['product_id'],$data['warehouse_id'],$this->session->userdata('guest_shipping_id')['address_form'], $data['price'],$data['qty'],$this->session->userdata('guest_shipping_id')['param_x']);
	    }else{
	    	$shipping_info = calculate_shipping_fee($data['selected_shipping_method_id'], $data['warehouse_id'], $data['product_id'], $data['qty'], $data['shipping_id_subdistrict']);
	    }

		$total_shipping_fee = $shipping_info['total_shipping_fee'];
		$subtotal = $data['qty'] * $data['price'];

	    //add new info to shipping cart session
	    $shipping_cart =  $this->session->userdata('shipping_cart');
		$shipping_cart[$data['rowid']]['qty'] = $this->input->post('qty');
		$shipping_cart[$data['rowid']]['subtotal'] = $subtotal;
		$shipping_cart[$data['rowid']]['shipping_fee'] = $total_shipping_fee;
		$this->session->set_userdata('shipping_cart', $shipping_cart);


		//remove session of shipping_cart
		$shipping_cart_session = $this->session->userdata('shipping_cart');

		//delete cart
		$data_update = array(
			'rowid' => $shipping_cart_session[$data['rowid']]['rowid'],
			'qty'   => $data['qty']
		 );

		 $this->cart->update($data_update);

        $this->load->view('ajax/ajax_change_shipping_fee', $data);
	}

	
	public function ajax_get_subtotal() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$this->load->helper('shipping');
		$this->load->helper('rajaongkir');

	    $data['qty'] = $this->input->post('qty');
	    $data['price'] = $this->input->post('price');
	    $data['rowid'] = $this->input->post('rowid');
	    $data['warehouse_id'] = $this->input->post('warehouse_id');
	    $data['shipping_id_subdistrict'] = $this->input->post('shipping_id_subdistrict');
	    $data['product_id'] = $this->input->post('product_id');
	    $data['selected_shipping_method_id'] = $this->input->post('selected_shipping_method_id');
	    $data['shipping_id_express'] = $this->input->post('shipping_id_express');


		//getcode 2hr & sameday
		$data['kode2hr'] = $this->db->select("*")->from("shipment_method")->where("id",'8')->get()->row()->id;
		$data['kodesame'] = $this->db->select("*")->from("shipment_method")->where("id",'9')->get()->row()->id;
		//getcode 2hr & sameday

		foreach ($this->session->userdata('shipping_cart') as $rowid => $cek_product_shipping) {

			if($rowid == $data['rowid']){
				if($data['selected_shipping_method_id'] == $data['kode2hr']) {
					// start 2hr delivery
					// $shipping_info = getAPI($shipping_id,$pinpoint,$custid,$productid,$warehouseid,$address,$price,$qty);
					// $shipping_info = getShippingExpress($data['kode2hr'],$cek_product_shipping['shipping_express'], $cek_product_shipping['warehouse_id']);
					$shipping_info = getAPI($data['kode2hr'],$this->session->userdata('guest_shipping_id')['pinpoin'],$this->session->userdata('customer')['customer_id'],$data['product_id'],$data['warehouse_id'],$this->session->userdata('guest_shipping_id')['address_form'],$data['price'],$data['qty'],$this->session->userdata('guest_shipping_id')['param_x']);
					
					$shipping_name = $shipping_info['shipping_name'];
					$total_shipping_fee = $shipping_info['total_shipping_fee'];
					// end 2hr delivery
				} else if($data['selected_shipping_method_id'] == $data['kodesame']){
					// start sameday delivery
					// $shipping_info = getShippingExpress($data['kodesame'],$cek_product_shipping['shipping_express'], $cek_product_shipping['warehouse_id']);
					$shipping_info = getAPI($data['kodesame'],$this->session->userdata('guest_shipping_id')['pinpoin'],$this->session->userdata('customer')['customer_id'],$data['product_id'],$data['warehouse_id'],$this->session->userdata('guest_shipping_id')['address_form'],$data['price'],$data['qty'],$this->session->userdata('guest_shipping_id')['param_x']);
					
					$shipping_name = $shipping_info['shipping_name'];
					$total_shipping_fee = $shipping_info['total_shipping_fee'];
					// end sameday delivery
				}else {
					$shipping_info = calculate_shipping_fee($data['selected_shipping_method_id'], $data['warehouse_id'], $data['product_id'], $data['qty'], $data['shipping_id_subdistrict']);
					$total_shipping_fee = $shipping_info['total_shipping_fee'];
				}
			}
		}
		$subtotal = $data['qty'] * $data['price'];

		//add new info to shipping cart session
	    $shipping_cart =  $this->session->userdata('shipping_cart');
		$shipping_cart[$data['rowid']]['qty'] = $this->input->post('qty');
		$shipping_cart[$data['rowid']]['subtotal'] = $subtotal;
		$shipping_cart[$data['rowid']]['chosen_shipping_id'] = $data['selected_shipping_method_id'];
		$shipping_cart[$data['rowid']]['shipping_fee'] = $total_shipping_fee;
		$this->session->set_userdata('shipping_cart', $shipping_cart);

		echo number_format($subtotal);
	}

	
	public function ajax_get_grandtotal() {
		$this->session->unset_userdata('chosen_point');
		$this->session->unset_userdata('chosen_point_discount');
		$this->session->unset_userdata('chosen_voucher_code');
		$this->session->unset_userdata('chosen_voucher_type');
		$this->session->unset_userdata('chosen_voucher_discount');
		$this->session->unset_userdata('total_categoryproduct_promo');
		$this->session->unset_userdata('total_brandproduct_promo');
		$this->session->unset_userdata('redeemed_voucher_amount');

		//get grand total for total products, total shipping fee, grand total

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$total_item_amount = 0;
		/*$final_total_shipping_fee = 0;*/
		foreach ($this->session->userdata('shipping_cart') as $rowid => $item) {
			$total_item_amount = $total_item_amount + $item['subtotal'];
		}

		/*new get final total shipping fee*/
		$this->load->helper('rajaongkir');
		$shipping_id_subdistrict = $this->input->post('subdistrict');
		$shipping_fee_array = array();
		foreach ($this->session->userdata('shipping_cart') as $item) {
			$shipping_fee_array[$item['chosen_shipping_id']][$item['warehouse_id']][] = $item;
		}


		//getcode 2hr & sameday
		$kode2hr = $this->db->select("*")->from("shipment_method")->where("id",'8')->get()->row()->id;
		$kodesame = $this->db->select("*")->from("shipment_method")->where("id",'9')->get()->row()->id;
		//getcode 2hr & sameday

		$final_total_shipping_fee = 0;
		$shipping_session = null;
		$shipping_session_index = 0;
		$note = '';
		foreach ($shipping_fee_array as $warehouse_sid) {
			$total_fee_shipping = 0;
			foreach ($warehouse_sid as $item1) {
				$total_fee_warehouse 	= 0;
				$total_weight_wids		= 0;
				$count_wsid = count($item1);
				for($a=0;$a<$count_wsid;$a++){
					$this->db
					->select('dimension_weight, dimension_length, dimension_width, dimension_height')
					->from('products')
					->where('id_products', $item1[$a]['id']);
					$product_dimension	= $this->db->get()->row();
					$product_weight 	= $product_dimension->dimension_weight;	//gram
					$product_length 	= $product_dimension->dimension_length;	//cm
					$product_width 		= $product_dimension->dimension_width;	//cm
					$product_height 	= $product_dimension->dimension_height; //cm
					//check if volume is bigger than weight
					$volume_weight 		= $product_length * $product_width * $product_height / 6000; //kg
					if(($volume_weight * 1000) >= $product_weight) {
						$weight = $volume_weight * 1000;
					} else {
						$weight = $product_weight;
					}
					$total_weight_gram 	= ceil($weight * $item1[$a]['qty']); //gram
					$total_weight_wids	= $total_weight_wids + $total_weight_gram;
				}
				$shipping_session[$shipping_session_index]['warehouse_id'] 	= $item1[0]['warehouse_id'];


				if($item1[0]['chosen_shipping_id'] == $kode2hr) {
					// start 2hr delivery
					$shipping_info = getAPI($kode2hr,$this->session->userdata('guest_shipping_id')['pinpoin'],$this->session->userdata('customer')['customer_id'],$item1[0]['id'],$item1[0]['warehouse_id'],$this->session->userdata('guest_shipping_id')['address_form'],$item1[0]['price'],$item1[0]['qty'],$this->session->userdata('guest_shipping_id')['param_x']);
					// 
					// $shipping_info = getShippingExpress($kode2hr,$item1[0]['shipping_express'], $item1[0]['warehouse_id']);
					$shipping_name = $shipping_info['shipping_name'];
					$total_shipping_fee = $shipping_info['total_shipping_fee'];
					$note = '<div style="background-color:#f2c246;font-size:10px;padding:5px;float:right">'.$shipping_info['note'].'</div>';
					// end 2hr delivery
				} else if($item1[0]['chosen_shipping_id'] == $kodesame){
					// start sameday delivery
					// $shipping_info = getShippingExpress($kodesame,$item1[0]['shipping_express'], $item1[0]['warehouse_id']);
					$shipping_info = getAPI($kodesame,$this->session->userdata('guest_shipping_id')['pinpoin'],$this->session->userdata('customer')['customer_id'],$item1[0]['id'],$item1[0]['warehouse_id'],$this->session->userdata('guest_shipping_id')['address_form'],$item1[0]['price'],$item1[0]['qty'],$this->session->userdata('guest_shipping_id')['param_x']);
					
					$shipping_name = $shipping_info['shipping_name'];
					$total_shipping_fee = $shipping_info['total_shipping_fee'];
					$note = '<div style="background-color:#f2c246;font-size:10px;padding:5px;float:right">'.$shipping_info['note'].'</div>';
					// end sameday delivery
				}else{
					//get shipping method name
					$this->db
					->select('name,shipper,carrier, service_code1, service_code2')
					->from('shipment_method')
					->where('id', $item1[0]['chosen_shipping_id']);
					$shipping_method 	= $this->db->get()->row();
					$shipping_name 		= $shipping_method->name;
					$shipping_carrier 	= $shipping_method->carrier;
					$shipping_shipper 	= $shipping_method->shipper;
					$service_code1 		= $shipping_method->service_code1;
					$service_code2 		= $shipping_method->service_code2;

					//get warehose sub district id
					$this->db->select('id_subdistrict')->from('warehouse')->where('id', $item1[0]['warehouse_id']);
					$warehouse_subdistrict_id = $this->db->get()->row()->id_subdistrict;
					$rajaongkir_cost = get_rajaongkir_ongkos($warehouse_subdistrict_id, $shipping_id_subdistrict, $total_weight_wids, $shipping_carrier);

					//check if weight is zero. If zero, then rajaongkir cannot proceed..
					if($total_weight_wids > 0) {
						//check which key has carrier name
						if($service_code2 != NULL) {
							if(count($rajaongkir_cost['rajaongkir']['results'][0]['costs']) != 0) {
								foreach($rajaongkir_cost['rajaongkir']['results'][0]['costs'] as $key => $result) {
									if($result['service'] == $service_code1 || $result['service'] == $service_code2) {
										$total_shipping_fee = $result['cost'][0]['value'];
										break;
									}
									else {
										$total_shipping_fee = 0; //service is not available
									}
								}
							}
							else {
								$total_shipping_fee = 0; //service is not available
							}
						}
						else {
							if(count($rajaongkir_cost['rajaongkir']['results'][0]['costs']) != 0) {
								foreach($rajaongkir_cost['rajaongkir']['results'][0]['costs'] as $key => $result) {
									if($result['service'] == $service_code1) {
										$total_shipping_fee = $result['cost'][0]['value'];
										break;
									}
									else {
										$total_shipping_fee = 0; //service is not available
									}
								}
							}
							else {
								$total_shipping_fee = 0; //service is not available
							}
						}
					}
					else {
						//total weight gram is zero
						$total_shipping_fee = 0; //service is not available
					}

				}


				$total_fee_warehouse 	= $total_fee_warehouse + $total_shipping_fee;
				$total_fee_shipping		= $total_fee_shipping + $total_fee_warehouse;

				$shipping_session[$shipping_session_index]['shipping_fee']	= $total_fee_warehouse;
				if($item1[0]['chosen_shipping_id'] == 5){
					$is_indent = "yes";
				}
				else{
					$is_indent = "no";
				}
				$shipping_session[$shipping_session_index]['is_indent'] 	= $is_indent;
				$shipping_session_index++;
			}
			$final_total_shipping_fee = $final_total_shipping_fee + $total_fee_shipping;
		}
		$this->session->set_userdata('shipping_session', $shipping_session);

		$this->session->set_userdata('total_shipping_fee', $final_total_shipping_fee);

		if($this->session->userdata('customer') && $this->session->userdata('customer')['customer_type'] == 'regular') {

			/*new get free fee shipping*/
			$this->load->helper('shipping');
			$free_shipping_fee 		= 0;
			$free_shipping_price 	= $this->db->select('free_shipping_type_subsidi')->from('configuration')->where('id_configuration',1)->get()->row()->free_shipping_type_subsidi;
			$free_shipping_type 	= $this->db->select('free_shipping_type')->from('configuration')->where('id_configuration',1)->get()->row()->free_shipping_type;
			if($free_shipping_type == 'region'){
				$selected_region_province = $this->db->select('province_id')->from('free_shipping_region')->where('configuration_id',1)->get()->result();
				foreach ($selected_region_province as $region_province) {
					if($region_province->province_id == $this->input->post('province')){
						if($free_shipping_price == 0){
							$free_shipping_fee = $final_total_shipping_fee;
						}
						else{
							$free_shipping_fee = $free_shipping_price;
						}
						break;
					}
				}
			}
			elseif($free_shipping_type == 'global'){
				$min_transaction = $this->db->select('min_transaction')->from('free_shipping_global')->where('configuration_id',1)->get()->row()->min_transaction;
				if($total_item_amount >= $min_transaction){
					if($free_shipping_price == 0){
						$free_shipping_fee = $final_total_shipping_fee;
					}
					else{
						$free_shipping_fee = $free_shipping_price;
					}
				}
				else{
					$free_shipping_fee = 0;
				}
			}
		}

		$this->session->set_userdata('free_shipping', $free_shipping_fee);

		$finalshippingfee = 0;
		$calculate_finalshippingfee = $final_total_shipping_fee - $free_shipping_fee;
		if($calculate_finalshippingfee > 0){
			$finalshippingfee = $calculate_finalshippingfee;
		}

		$final_grand_total = 0;
		$grand_total = $total_item_amount + $finalshippingfee;
		if($grand_total > 0){
			$final_grand_total = $grand_total;
		}

		/*$grand_total = $total_item_amount + $final_total_shipping_fee;*/

		$data_total = array(
			'total_item_amount' => number_format($total_item_amount),
			'total_shipping_fee' => number_format($final_total_shipping_fee),
			'total_free_shipping_fee' => number_format($free_shipping_fee),
			'finalshippingfee' => number_format($finalshippingfee),
			'grand_total' => number_format($final_grand_total),
			'note' => $note
		);

		// Data Layer
		$data_total['datalayer'] = array(
			'event' => 'EEcheckout'
		);

		$data_total['datalayer']['ecommerce']['checkout']['actionField'] = array(
			'step' => 2
		);
		
		$data_total['datalayer']['ecommerce']['checkout']['products'] = array();
		$i=1;
		foreach ($this->session->userdata('shipping_cart') as $items){
			$this->db->select(" products.title, products.product_code AS code, brands.brand AS brnd, cp.category AS cat,  products.alias as aliases , case when is_sale = 'yes' then discounted_price else sale_price end as price 
			from products
			join brands on brands.id_brands = products.brand_id
			left JOIN(
			SELECT * FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			and categories.parent IS NULL
			AND categories.id_categories IN(364,389,410,440,489,500,525,547)
			) cp ON cp.id_product = products.id_products 
			where products.id_products = ".$items['id']."
			GROUP BY products.id_products
							   ");
			$sub = $this->db->get()->row();
			$query = "";
			$query .= "(SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
			and categories.id_categories IN(364,389,410,440,489,500,525,547) )";
		
			$query .= "UNION all";
	
			$query .= "(
			SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
				and categories.parent IN(364,389,410,440,489,500,525,547) 
			)";
		
			$query .= "UNION all";
	
			$query .= "(SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
			and categories.parent IN(
				SELECT id_categories 
				FROM categories 
				where categories.parent IN(364,389,410,440,489,500,525,547)
			) )"; 
	
		
			$cat = $this->db->query($query)->result();
			
			$this->db->select("* from shipment_method
			where id = '".$items["chosen_shipping_id"]."'
			");
			$shipment = $this->db->get()->row();
			$ecomm_id_arr["id"] = $sub->code;
			$ecomm_id_arr["name"] = $items["name"];
			$ecomm_id_arr["price"] = $items["subtotal"];
			$ecomm_id_arr["brand"] = $sub->brnd;
			$ecomm_id_arr["category"] = $cat[0]->category.' - '.$cat[1]->category.' - '.$cat[2]->category;
			if(!empty($shipment->carrier)){
			$ecomm_id_arr["carrier"] = strtoupper($shipment->carrier);
			}else{
			$ecomm_id_arr["carrier"] = strtoupper($shipment->name);
			} 
			$ecomm_id_arr["position"] = $i;
			$ecomm_id_arr["quantity"] = (int)$items["qty"];
			array_push($data_total['datalayer']['ecommerce']['checkout']['products'], $ecomm_id_arr);
			$i++;
		}

	
	$user_ip = $this->input->ip_address();

    // if($user_ip == '127.0.0.1'){
	//check active rewards event
	if($this->session->userdata('customer')['customer_type'] == 'regular') {
		$this->db->select('*')->from('point_rewards')->where('id_point_rewards', 2);
		$check_event = $this->db->get()->row();
		if ($check_event->active == 'yes') {
			$hasil_mod = fmod($total_item_amount, 100000);
			$noundian = intval($total_item_amount / 100000);
			$sisa_mod = 100000 - $hasil_mod;
			// kurang belanja  
			if ($sisa_mod <= $check_event->conversion) {  
				$kupon  = $noundian;
				$data_total['event'] = '
				<div class="row pt-2">
					<div class="col-md-3">	
					</div>
					<div class="col-md-9 col-sm-12 col-12">
						<div class="mb-0 ml-lg-3" style="border: 1px solid black;background: #e5e5e5;color: orange;font-weight: 500;padding:5px 0px 5px 20px;border-radius:7px">
						<p class="m-0"><span>Potensi kupon yang di dapat '.$kupon.'</span></p>
						<p class="m-0">Belanja Rp. '.$sisa_mod.' lagi untuk mendapatkan 1 kupon tambahan</p>
						</div>
					</div>
				</div>';
			}else{
				$data_total['event'] = '';
			}
		}
	  }
	// }

		echo json_encode($data_total);
	}

	public function update_shipping_address() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		if(!empty($this->input->post('shipping_address'))) {
			$data = array(
			'shipping_address' => $this->input->post('shipping_address'),
			);
			$this->db->where('id_customers', $this->input->post('customer_id'));
			$this->db->update('customers', $data);
		}

		echo 'success';
	}

	public function update_shipping_phone() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		if(!empty($this->input->post('shipping_phone'))) {
			$data = array(
			'shipping_phone' => $this->input->post('shipping_phone'),
			);
			$this->db->where('id_customers', $this->input->post('customer_id'));
			$this->db->update('customers', $data);
		}

		echo 'success shipping phone';
	}

	public function update_shipping_postcode() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		if(!empty($this->input->post('shipping_postcode'))) {
			$data = array(
			'shipping_postcode' => $this->input->post('shipping_postcode'),
			);
			$this->db->where('id_customers', $this->input->post('customer_id'));
			$this->db->update('customers', $data);
		}

		echo 'success';

	}

	public function ajax_get_suggest_product() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$data['ketik'] = $search_data = $this->security->xss_clean($this->input->post('search_data'));

		//search by area firstly..
		$this->db->select('*');
		$this->db->from('products');
    	$this->db->like('title', $search_data);
    	$this->db->where('product_status', '1');
    	$this->db->order_by('rand()');
		$this->db->limit(5);
		$data['result_products'] = $this->db->get()->result();

		$this->db->select('*');
		$this->db->from('brands');
    	$this->db->like('brand', $search_data);
    	$this->db->where('status', '1');
		$this->db->limit(3);
		$data['result_brands'] = $this->db->get()->result();

		if(!empty($data['result_brands'])){
			$this->load->view('ajax/ajax_get_suggest_product', $data);
		} else if (!empty($data['result_products'])) {
			$this->load->view('ajax/ajax_get_suggest_product', $data);
		}
		else {
			echo "<li>Product Not Available...</li>";
		}
	}

	public function ajax_admin_get_product() {

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$search_data = $this->security->xss_clean($this->input->post('search_data'));

		$this->db->select('*');
		$this->db->from('products');
    	$this->db->like('title', $search_data);
    	$this->db->order_by('title', 'ASC');
		$this->db->limit(10);
		$data['result_products'] = $this->db->get()->result();

		if ($data['result_products'] != null) {
			$this->load->view('ajax/ajax_admin_get_product', $data);
		}
		else {
			echo '<p>Product Not Available...</p>';
		}
	}

	/*new ajax voucher and point rewards*/
	public function ajax_set_voucher() {

		error_reporting(0);
		$this->session->unset_userdata('chosen_voucher_code');
		$this->session->unset_userdata('chosen_voucher_type');
		$this->session->unset_userdata('chosen_voucher_discount');
		$this->session->unset_userdata('total_categoryproduct_promo');
		$this->session->unset_userdata('total_brandproduct_promo');
		$this->session->unset_userdata('redeemed_voucher_amount');

		$input_voucher		= $this->security->xss_clean($this->input->post('voucher'));
		$pointprice			= $this->security->xss_clean($this->input->post('pointprice'));
		$id_customer 		= $this->security->xss_clean($this->input->post('id_customer'));
		$voucher_price 		= 0;
		$voucher_discount 	= '';
		$alert				= '';

		//rein voucher point reward SESSION
		$this->session->set_userdata('sessvoucher',$input_voucher);
		//rein voucher point reward SESSION

		if($this->session->userdata('customer')['customer_type'] == 'guest') {
			$alert = 'Silahkan Login untuk menggunakan voucher';
		}



		//check if the voucher quantity already empty
		$this->db->select('qty_ready')->from('vouchers')->where('voucher_code', $input_voucher);
		$qty_ready = $this->db->get()->row()->qty_ready;
		if ($qty_ready == 0 && $qty_ready != NULL) {
			$alert = 'Voucher Code Used Up!';
		}

		//check if the voucher usage already exceed max customer usage
		//get max quantity
		$this->db->select('maxqty_per_person')->from('vouchers')->where('voucher_code', $input_voucher);
		$maxqty_per_person = $this->db->get()->row()->maxqty_per_person;

		if ($maxqty_per_person != NULL) {

			//get voucher id
			$this->db->select('id_vouchers')->from('vouchers')->where('voucher_code', $input_voucher);
			$voucher_id = (int) $this->db->get()->row()->id_vouchers;

			//check on customer voucher_user table, if exist
			$this->db->select('*')->from('voucher_users')->where('voucher_id', $voucher_id)->where('customer_id', $id_customer);
			$count_user = $this->db->get()->num_rows();

			if ($count_user > 0) {
				//get current voucher usage
				$this->db->select('voucher_used')->from('voucher_users')->where('voucher_id', $voucher_id)->where('customer_id', $id_customer);
				$voucher_used = (int) $this->db->get()->row()->voucher_used;

				//if the user voucher already exceed max quota
				if ($voucher_used >= $maxqty_per_person) {
					$alert = 'You have used max allowed no. of vouchers / customer';
				}
			}
		}

		//check if the voucher usage already exceed max customer usage per day
		//get max quantity per day
		$voc = $this->Shipment_express_m->get_voucher($input_voucher);
		$maxqty_per_day = $this->Shipment_express_m->get_voucher_usage($voc->id_vouchers, $id_customer);  

		if ($voc->limit_per_day > 0) { 
			if ($voc->limit_per_day != NULL) {
		
			//if the user voucher already exceed max quota perday
				if ($maxqty_per_day->vouchers_usage >= $voc->limit_per_day) {
					$alert = 'Kuota Voucher untuk hari ini telah habis'; 
				} 
				
			}
		} 
 
		/*get grand total without shipping fee*/
		$grand_total_without_shipping = 0;
		foreach ($this->session->userdata('shipping_cart') as $rowid => $item) {
			$grand_total_without_shipping = $grand_total_without_shipping + $item['subtotal'];
		}

		//cek jika discount value lebih besar dari total order without shipping, maka di cegat
		/*$this->db->select('discount_value')->from('vouchers')->where('voucher_code', $input_voucher);
		$cek_discount_value = $this->db->get()->row()->discount_value;
		if($cek_discount_value > $grand_total_without_shipping) {
			$alert = 'Harap menambahkan jumlah transaksi anda min. IDR '.number_format($cek_discount_value).'<br/> untuk menggunakan voucher ini';
		}*/

		//get minimum order from voucher table
		$this->db->select('min_order')->from('vouchers')->where('voucher_code', $input_voucher);
		$min_order = $this->db->get()->row()->min_order;

		if ($min_order != NULL) {
			if ($grand_total_without_shipping < (int) $min_order) {
				$alert = 'Sorry Your order amount is not enough';
			}
		}

		/*VOUCHER VALIDATION*/
		//$get_voucher = $this->db->select('voucher_code')->from('vouchers')->where('voucher_code like binary '.'"'.$input_voucher.'"'.'')->get()->row();
		$get_voucher = $this->db->select('voucher_code')->from('vouchers')->where('voucher_code',$input_voucher)->get()->row();
		if (count($get_voucher) == 0) {
			$alert = 'Voucher Code Not Exist!';
		}

		//Check for expired date
		//get expired date for this voucher
		$this->db->select('expired_date')->from('vouchers')->where('voucher_code', $input_voucher);
		$expired_date = $this->db->get()->row()->expired_date;
		if ($expired_date != NULL) {
			$expired_date_numbers = strtotime($expired_date);
			$current_date_numbers = strtotime(date('Y-m-d H:i:s'));
			if ($current_date_numbers > $expired_date_numbers) {
				$alert = 'Sorry Your Voucher Code Already Expired';
			}
		}

		//get voucher type
		$this->db->select('voucher_type')->from('vouchers')->where('voucher_code', $input_voucher);
		$voucher_type = $this->db->get()->row()->voucher_type;

		//id_customer

		switch ($voucher_type) {
			case 'normal promo':
				# do nothing...
				break;

			case 'birthday promo':
				//get birthmonth
				$this->db->select('birthmonth')->from('vouchers')->where('voucher_code', $input_voucher);
				$voucher_birthmonth = (int) $this->db->get()->row()->birthmonth;
				//get customer birthmonth
				$this->db->select('birthday')->from('customers')->where('id_customers', $id_customer);
				$birthday = $this->db->get()->row()->birthday;
				$birthday_array = explode('-',$birthday);
				$customer_birthmonth = (int) $birthday_array[1];
				if ($voucher_birthmonth != $customer_birthmonth) {
					$alert = 'Sorry It is not Your Birth Month';
				}
				break;

			case 'gender promo':
				//get gender
				$this->db->select('gender')->from('vouchers')->where('voucher_code', $input_voucher);
				$voucher_gender = $this->db->get()->row()->gender;
				//get customer gender
				$this->db->select('sex_type')->from('customers')->where('id_customers', $id_customer);
				$customer_gender = $this->db->get()->row()->sex_type;
				/*if ($customer_title == 'mr') {
					$customer_gender = 'male';
				} else {
					$customer_gender = 'female';
				}*/
				if ($voucher_gender != $customer_gender) {
					$alert = 'Sorry It is not Your Gender';
				}
				break;

			case 'time promo':
				//strtotime means convert date string d-m-Y to time froom 1970 unix time
				//get start promo time
				$this->db->select('promostart')->from('vouchers')->where('voucher_code', $input_voucher);
				$promostart = strtotime($this->db->get()->row()->promostart);
				//get end promo time
				$this->db->select('promoend')->from('vouchers')->where('voucher_code', $input_voucher);
				$promoend = strtotime($this->db->get()->row()->promoend);
				//get current date and time
				$currentdatetime = strtotime(date('Y-m-d H:i:s'));
				if ($currentdatetime > $promostart && $currentdatetime < $promoend) {
					//time range is correct, promo is valid
					//do nothing..
				} else {
					//time range is false, so promo is not valid
					$alert = 'Sorry Promo Time expired';
				}
				break;

			case 'province promo':
				//get province_id
				$this->db->select('provincepromo')->from('vouchers')->where('voucher_code', $input_voucher);
				$voucher_province_id = (int) $this->db->get()->row()->provincepromo;
				//get customer province_id
				$this->db->select('shipping_id_province')->from('customers')->where('id_customers', $id_customer);
				$customer_shipping_id_province = $this->db->get()->row()->shipping_id_province;
				if ($voucher_province_id != $customer_shipping_id_province) {
					$alert = 'Sorry It is not Your Province';
				}
				break;

			case 'quantity promo':
				//get min quantity at checkout
				$this->db->select('quantitypromo')->from('vouchers')->where('voucher_code', $input_voucher);
				$voucher_quantitypromo = (int) $this->db->get()->row()->quantitypromo;
				//get current combined product quantity at cart
				$cart = $this->session->userdata('shipping_cart');
				$total_cart_quantity = 0;
				foreach ($cart as $item) {
					$total_cart_quantity = $total_cart_quantity + $item['qty'];
				}
				if ($total_cart_quantity < $voucher_quantitypromo) {
					$alert = 'Sorry Your Total Cart Quantity not enough';
				}
				break;

			case 'category promo':

				//get categories id from voucher code
				$this->db->select('categorypromo')->from('vouchers')->where('voucher_code', $input_voucher);
				$categories = $this->db->get()->row()->categorypromo;
				$categories_array = explode(',', $categories);
				//get current cart content
				$cart = $this->session->userdata('shipping_cart');
				$count_category_exist = 0;
				foreach ($cart as $item) {
					//get category_id from each item
					$this->db->select('id_category')->from('category_product')->where('id_product', $item['id']);
					$products_category = $this->db->get()->result();
					foreach($products_category as $category_item) {
						if(in_array($category_item->id_category, $categories_array)) {
							$count_category_exist = $count_category_exist + 1;
						}
					}
				}
				if($count_category_exist == 0) {
					$alert = 'Sorry You did not choose Products with Promoted Category';
				}
				break;

			case 'brand promo':
				//get brands id from voucher code
				$this->db->select('brandpromo')->from('vouchers')->where('voucher_code', $input_voucher);
				$brands = $this->db->get()->row()->brandpromo;
				$brands_array = explode(',', $brands);
				//get current cart content
				$cart = $this->session->userdata('shipping_cart');
				$count_brand_exist = 0;
				foreach ($cart as $item) {
					//get brand_id from each item
					$this->db->select('brand_id')->from('products')->where('id_products', $item['id']);
					$product_brand_id = $this->db->get()->row()->brand_id;

					if(in_array($product_brand_id, $brands_array)) {
						$count_brand_exist = $count_brand_exist + 1;
					}
				}
				if($count_brand_exist == 0) {
					$alert = 'Sorry You did not choose Products with Promoted Brand';
				}
				break;
			case 'bin promo':
				//get categories id from voucher code
				$this->db->select('categorypromo')->from('vouchers')->where('voucher_code', $input_voucher);
				$categories = $this->db->get()->row()->categorypromo;
				if(count($categories) > 0){
					$categories_array = explode(',', $categories);
					//get current cart content
					$cart = $this->session->userdata('shipping_cart');
					$count_category_exist = 0;
					foreach ($cart as $item) {
						//get category_id from each item
						$this->db->select('id_category')->from('category_product')->where('id_product', $item['id']);
						$products_category = $this->db->get()->result();
						foreach($products_category as $category_item) {
							if(in_array($category_item->id_category, $categories_array)) {
								$count_category_exist = $count_category_exist + 1;
							}
						}
					}
					if($count_category_exist == 0) {
						$alert = 'Sorry You did not choose Products with Promoted Category';
						break;
					}
				}

				//get repeat from voucher code
				$day = $this->db->select("DAYNAME(NOW()) day")->get()->row()->day;
				$this->db->select('repeat_weekly')->from('vouchers')->where('voucher_code', $input_voucher);
				$repeats = $this->db->get()->row()->repeat_weekly;
				if(count($repeats) > 0){
					$repeats_ex = explode(',', $repeats);
					//get current cart content
					$cart = $this->session->userdata('shipping_cart');
					$count_category_exist = 0;
					//get category_id from each item
					if(in_array(strtolower($day), $repeats_ex)) {
						$count_category_exist = $count_category_exist + 1;
					}
					if($count_category_exist == 0) {
						$alert = 'Sorry Promo Time expired';
					}
				}
				break;
		}
		/*VOUCHER VALIDATION*/

		/*if alert is null, is mean validation false*/
		if($alert != ''){
			$this->session->unset_userdata('chosen_voucher_code');
			$this->session->unset_userdata('chosen_voucher_type');
			$this->session->unset_userdata('chosen_voucher_discount');
			$this->session->unset_userdata('total_categoryproduct_promo');
			$this->session->unset_userdata('total_brandproduct_promo');
			$this->session->unset_userdata('redeemed_voucher_amount');
			$this->session->unset_userdata('sessvoucher');
		}

		/*if alert null, is mean validation true*/
		else{
			/*SET VOUCHER VALUE*/
			//get discount type and amount
			$this->db->select('*')->from('vouchers')->where('voucher_code', $input_voucher);
			$voucher = $this->db->get()->row();

			$this->session->set_userdata('chosen_voucher_code', $voucher->voucher_code);
			$this->session->set_userdata('chosen_voucher_type', $voucher->discount_type);
			$this->session->set_userdata('chosen_voucher_discount', (int) $voucher->discount_value);
			//$voucher_price = (int) $voucher->discount_value;

			if($voucher->voucher_type == 'category promo') {

				if($voucher->discount_type == 'percentage') {
					$voucher_discount = '('.$voucher->discount_value.'%)';

					//discount type by percentage..here need to calculate discount for specific products whose categories are matched only..
					$discount_rate = $voucher->discount_value;
					//get categories id from voucher code
					$this->db->select('categorypromo')->from('vouchers')->where('voucher_code', $input_voucher);
					$categories = $this->db->get()->row()->categorypromo;
					$categories_array = explode(',', $categories);

					//get current cart content
					$cart = $this->session->userdata('shipping_cart');

					$total_amount_promoted_categories = 0;

					foreach ($cart as $item) {

						//check if this item has category which is match with $categories_array
						$this->db->select('id_category')->from('category_product')->where('id_product', $item['id']);
						$categories_id = $this->db->get()->result();

						$count_category_id = 0;
						foreach($categories_id as $category_id) {

							if(in_array($category_id->id_category, $categories_array)) {
								$count_category_id = $count_category_id + 1;
							}
						}

						if($count_category_id > 0) {
							//this $item has category which is match with $categories_array, so we can add to percentage discounts
							/*$total_amount_promoted_categories = $total_amount_promoted_categories + ($item['price'] * $item['qty'] * $discount_rate / 100);*/
							$voucher_price = $voucher_price + ($item['price'] * $item['qty'] * $discount_rate / 100);
						}
					}
					$this->session->set_userdata('total_categoryproduct_promo', (int) $voucher_price);
				}
				else{
					$voucher_discount = '';
					$voucher_price = (int) $voucher->discount_value;
				}
			}

			elseif($voucher->voucher_type == 'brand promo') {

				if($voucher->discount_type == 'percentage') {
					$voucher_discount = '('.$voucher->discount_value.'%)';

					//discount type by percentage..here need to calculate discount for specific products whose brands are matched only..
					$discount_rate = $voucher->discount_value;
					//get brands id from voucher code
					$this->db->select('brandpromo')->from('vouchers')->where('voucher_code', $input_voucher);
					$brands = $this->db->get()->row()->brandpromo;
					$brands_array = explode(',', $brands);

					//get current cart content
					$cart = $this->session->userdata('shipping_cart');

					$total_amount_promoted_brands = 0;

					foreach ($cart as $item) {

						//check if this item has brand which is match with $brands_array
						$this->db->select('brand_id')->from('products')->where('id_products', $item['id']);
						$brand_id = $this->db->get()->row()->brand_id;

						if(in_array($brand_id, $brands_array)) {

							//this $item has brand which is match with $brands_array, so we can add to percentage discounts
							/*$total_amount_promoted_brands = $total_amount_promoted_brands + ($item['price'] * $item['qty'] * $discount_rate / 100);*/
							$voucher_price = $voucher_price + ($item['price'] * $item['qty'] * $discount_rate / 100);
						}
					}
					$this->session->set_userdata('total_brandproduct_promo', (int) $voucher_price);
				}
				else{
					$voucher_discount = '';
					$voucher_price = (int) $voucher->discount_value;
				}
			}else if($voucher->voucher_type == 'bin promo'){
				if($voucher->discount_type == 'percentage') {
					$voucher_discount = '('.$voucher->discount_value.'%)';

					//discount type by percentage..here need to calculate discount for specific products whose categories are matched only..
					$discount_rate = $voucher->discount_value;
					//get categories id from voucher code
					$this->db->select('categorypromo')->from('vouchers')->where('voucher_code', $input_voucher);
					$categories = $this->db->get()->row()->categorypromo;
					if(count($categories) > 0){
						$categories_array = explode(',', $categories);
						//get current cart content
						$cart = $this->session->userdata('shipping_cart');
	
						$total_amount_promoted_categories = 0;
	
						foreach ($cart as $item) {
	
							//check if this item has category which is match with $categories_array
							$this->db->select('id_category')->from('category_product')->where('id_product', $item['id']);
							$categories_id = $this->db->get()->result();
	
							$count_category_id = 0;
							foreach($categories_id as $category_id) {
	
								if(in_array($category_id->id_category, $categories_array)) {
									$count_category_id = $count_category_id + 1;
								}
							}
	
							if($count_category_id > 0) {
								//this $item has category which is match with $categories_array, so we can add to percentage discounts
								/*$total_amount_promoted_categories = $total_amount_promoted_categories + ($item['price'] * $item['qty'] * $discount_rate / 100);*/
								$voucher_price = $voucher_price + ($item['price'] * $item['qty'] * $discount_rate / 100);
							}
						}
						$this->session->set_userdata('total_categoryproduct_promo', (int) $voucher_price);
					}else{
						$voucher_discount = '('.$voucher->discount_value.'%)';
						$product_grand_total = 0;
						foreach ($this->session->userdata('shipping_cart') as $rowid => $item) {
							$product_grand_total = $product_grand_total + $item['subtotal'];
						}
						$voucher_price = ($voucher->discount_value/100) * $product_grand_total;
					}
				}
				else{
					$voucher_discount = '';
					$voucher_price = (int) $voucher->discount_value;
				}
			}
			else{
				if($voucher->discount_type == 'percentage') {
					$voucher_discount = '('.$voucher->discount_value.'%)';
					$product_grand_total = 0;
					foreach ($this->session->userdata('shipping_cart') as $rowid => $item) {
						$product_grand_total = $product_grand_total + $item['subtotal'];
					}
					$voucher_price = ($voucher->discount_value/100) * $product_grand_total;
				}
				else{
					$voucher_discount = '';
					$voucher_price = (int) $voucher->discount_value;
				}
			}

			
			if($voucher->max_disc > 0){
				if($voucher->max_disc <=  $voucher_price){
					$voucher_price = $voucher->max_disc;
				}
			}
			
			$this->session->set_userdata('redeemed_voucher_amount', $voucher_price);

			/*SET VOUCHER VALUE*/
		}

		/*hitung grand total include point rewards*/
		$total_item_amount = 0;
		foreach ($this->session->userdata('shipping_cart') as $rowid => $item) {
			$total_item_amount = $total_item_amount + $item['subtotal'];
		}

		/*if($voucher_price > $total_item_amount) {
			$alert = 'Jumlah Voucher Yang Ditukar <br/>IDR '.number_format($voucher_price).'<br/> Melebihi Total Pembelian';
			$voucher_price	= 0;
		}*/


		//getcode 2hr & sameday
		$kode2hr = $this->db->select("*")->from("shipment_method")->where("id",'8')->get()->row()->id;
		$kodesame = $this->db->select("*")->from("shipment_method")->where("id",'9')->get()->row()->id;
		//getcode 2hr & sameday

		/*new get final total shipping fee*/
		$this->load->helper('rajaongkir');
		$shipping_id_subdistrict = $this->input->post('subdistrict');
		$shipping_fee_array = array();
		foreach ($this->session->userdata('shipping_cart') as $item) {
			$shipping_fee_array[$item['chosen_shipping_id']][$item['warehouse_id']][] = $item;
		}

		$final_total_shipping_fee = 0;
		$shipping_session = null;
		$shipping_session_index = 0;
		foreach ($shipping_fee_array as $warehouse_sid) {
			$total_fee_shipping = 0;
			foreach ($warehouse_sid as $item1) {
				$total_fee_warehouse 	= 0;
				$total_weight_wids		= 0;
				$count_wsid = count($item1);
				for($a=0;$a<$count_wsid;$a++){
					$this->db
					->select('dimension_weight, dimension_length, dimension_width, dimension_height')
					->from('products')
					->where('id_products', $item1[$a]['id']);
					$product_dimension	= $this->db->get()->row();
					$product_weight 	= $product_dimension->dimension_weight;	//gram
					$product_length 	= $product_dimension->dimension_length;	//cm
					$product_width 		= $product_dimension->dimension_width;	//cm
					$product_height 	= $product_dimension->dimension_height; //cm
					//check if volume is bigger than weight
					$volume_weight 		= $product_length * $product_width * $product_height / 6000; //kg
					if(($volume_weight * 1000) >= $product_weight) {
						$weight = $volume_weight * 1000;
					} else {
						$weight = $product_weight;
					}
					$total_weight_gram 	= ceil($weight * $item1[$a]['qty']); //gram
					$total_weight_wids	= $total_weight_wids + $total_weight_gram;
				}
				$shipping_session[$shipping_session_index]['warehouse_id'] 	= $item1[0]['warehouse_id'];

				if($item1[0]['chosen_shipping_id'] == $kode2hr) {
					// start 2hr delivery
					// $shipping_info = getAPI($shipping_id,$pinpoint,$custid,$productid,$warehouseid,$address,$price,$qty);
					// $shipping_info = getShippingExpress($kode2hr,$item1[0]['shipping_express'], $item1[0]['warehouse_id']);
					$shipping_info = getAPI($kode2hr,$this->session->userdata('guest_shipping_id')['pinpoin'],$this->session->userdata('customer')['customer_id'],$item1[0]['id'],$item1[0]['warehouse_id'],$this->session->userdata('guest_shipping_id')['address_form'],$item1[0]['price'],$item1[0]['qty'],$this->session->userdata('guest_shipping_id')['param_x']);

					$shipping_name = $shipping_info['shipping_name'];
					$total_shipping_fee = $shipping_info['total_shipping_fee'];
					// end 2hr delivery
				} else if($item1[0]['chosen_shipping_id'] == $kodesame){
					// start sameday delivery
					// $shipping_info = getShippingExpress($kodesame,$item1[0]['shipping_express'], $item1[0]['warehouse_id']);
					$shipping_info = getAPI($kodesame,$this->session->userdata('guest_shipping_id')['pinpoin'],$this->session->userdata('customer')['customer_id'],$item1[0]['id'],$item1[0]['warehouse_id'],$this->session->userdata('guest_shipping_id')['address_form'],$item1[0]['price'],$item1[0]['qty'],$this->session->userdata('guest_shipping_id')['param_x']);
					
					$shipping_name = $shipping_info['shipping_name'];
					$total_shipping_fee = $shipping_info['total_shipping_fee'];
					// end sameday delivery
				}else{
					//get shipping method name
					$this->db
					->select('name,shipper,carrier, service_code1, service_code2')
					->from('shipment_method')
					->where('id', $item1[0]['chosen_shipping_id']);
					$shipping_method 	= $this->db->get()->row();
					$shipping_name 		= $shipping_method->name;
					$shipping_carrier 	= $shipping_method->carrier;
					$shipping_shipper 	= $shipping_method->shipper;
					$service_code1 		= $shipping_method->service_code1;
					$service_code2 		= $shipping_method->service_code2;

					//get warehose sub district id
					$this->db->select('id_subdistrict')->from('warehouse')->where('id', $item1[0]['warehouse_id']);
					$warehouse_subdistrict_id = $this->db->get()->row()->id_subdistrict;
					$rajaongkir_cost = get_rajaongkir_ongkos($warehouse_subdistrict_id, $shipping_id_subdistrict, $total_weight_wids, $shipping_carrier);

					//check if weight is zero. If zero, then rajaongkir cannot proceed..
					if($total_weight_wids > 0) {
						//check which key has carrier name
						if($service_code2 != NULL) {
							if(count($rajaongkir_cost['rajaongkir']['results'][0]['costs']) != 0) {
								foreach($rajaongkir_cost['rajaongkir']['results'][0]['costs'] as $key => $result) {
									if($result['service'] == $service_code1 || $result['service'] == $service_code2) {
										$total_shipping_fee = $result['cost'][0]['value'];
										break;
									}
									else {
										$total_shipping_fee = 0; //service is not available
									}
								}
							}
							else {
								$total_shipping_fee = 0; //service is not available
							}
						}
						else {
							if(count($rajaongkir_cost['rajaongkir']['results'][0]['costs']) != 0) {
								foreach($rajaongkir_cost['rajaongkir']['results'][0]['costs'] as $key => $result) {
									if($result['service'] == $service_code1) {
										$total_shipping_fee = $result['cost'][0]['value'];
										break;
									}
									else {
										$total_shipping_fee = 0; //service is not available
									}
								}
							}
							else {
								$total_shipping_fee = 0; //service is not available
							}
						}
					}
					else {
						//total weight gram is zero
						$total_shipping_fee = 0; //service is not available
					}
				}
				$total_fee_warehouse 	= $total_fee_warehouse + $total_shipping_fee;
				$total_fee_shipping		= $total_fee_shipping + $total_fee_warehouse;

				$shipping_session[$shipping_session_index]['shipping_fee']	= $total_fee_warehouse;
				if($item1[0]['chosen_shipping_id'] == 5){
					$is_indent = "yes";
				}
				else{
					$is_indent = "no";
				}
				$shipping_session[$shipping_session_index]['is_indent'] 	= $is_indent;
				$shipping_session_index++;
			}
			$final_total_shipping_fee = $final_total_shipping_fee + $total_fee_shipping;
		}
		$this->session->set_userdata('shipping_session', $shipping_session);

		$this->session->set_userdata('total_shipping_fee', $final_total_shipping_fee);

		if($this->session->userdata('customer') && $this->session->userdata('customer')['customer_type'] == 'regular') {

			/*new get free fee shipping*/
			$this->load->helper('shipping');
			$free_shipping_fee 		= 0;
			$free_shipping_price 	= $this->db->select('free_shipping_type_subsidi')->from('configuration')->where('id_configuration',1)->get()->row()->free_shipping_type_subsidi;
			$free_shipping_type 	= $this->db->select('free_shipping_type')->from('configuration')->where('id_configuration',1)->get()->row()->free_shipping_type;
			if($free_shipping_type == 'region'){
				$selected_region_province = $this->db->select('province_id')->from('free_shipping_region')->where('configuration_id',1)->get()->result();
				foreach ($selected_region_province as $region_province) {
					if($region_province->province_id == $this->input->post('province')){
						if($free_shipping_price == 0){
							$free_shipping_fee = $final_total_shipping_fee;
						}
						else{
							$free_shipping_fee = $free_shipping_price;
						}
						break;
					}
				}
			}
			elseif($free_shipping_type == 'global'){
				$min_transaction = $this->db->select('min_transaction')->from('free_shipping_global')->where('configuration_id',1)->get()->row()->min_transaction;
				if($total_item_amount >= $min_transaction){
					if($free_shipping_price == 0){
						$free_shipping_fee = $final_total_shipping_fee;
					}
					else{
						$free_shipping_fee = $free_shipping_price;
					}
				}
				else{
					$free_shipping_fee = 0;
				}
			}
		}

		$this->session->set_userdata('free_shipping', $free_shipping_fee);

		$finalshippingfee = 0;
		$calculate_finalshippingfee = $final_total_shipping_fee - $free_shipping_fee;
		if($calculate_finalshippingfee > 0){
			$finalshippingfee = $calculate_finalshippingfee;
		}

		$first_total = 0;
		if(($total_item_amount - $voucher_price - $pointprice) < 0 ){
			$first_total =  0;
		}else{
			$first_total = $total_item_amount - $voucher_price - $pointprice;
		}

		$final_grand_total = 0;
		$grand_total = ($first_total) + $finalshippingfee;
		if($grand_total > 0){
			$final_grand_total = $grand_total;
		} else {
			//check if finalshippingfee is > 0
			if($finalshippingfee > 0) {
				$final_grand_total = $finalshippingfee;
			}
		}

		$data_total = array(
			'total_item_amount'			=> number_format($total_item_amount),
			'voucher_discount'			=> $voucher_discount,
			'voucherprice'				=> number_format($voucher_price),
			'voucherprice_input'		=> $voucher_price,
			'alert'						=> $alert,
			'firsttotal'				=> number_format($first_total),
			'total_shipping_fee' 		=> number_format($final_total_shipping_fee),
			'total_free_shipping_fee' 	=> number_format($free_shipping_fee),
			'finalshippingfee' 			=> number_format($finalshippingfee),
			'grand_total' 				=> number_format($final_grand_total),
			//rein voucher point reward
			'sessvoucher' => $this->session->userdata('sessvoucher')
			//rein voucher point reward
		);

		// Data Layer
		$data_total['datalayer'] = array(
			'event' => 'EEcheckout'
		);
		$data_total['datalayer']['ecommerce']['checkout']['actionField'] = array(
			'step' => 2
		);
		$data_total['datalayer']['ecommerce']['checkout']['products'] = array();
		$i=1;
		foreach ($this->session->userdata('shipping_cart') as $items){
			$this->db->select(" products.title, products.product_code AS code, brands.brand AS brnd, cp.category AS cat,  products.alias as aliases , case when is_sale = 'yes' then discounted_price else sale_price end as price 
			from products
			join brands on brands.id_brands = products.brand_id
			left JOIN(
			SELECT * FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			and categories.parent IS NULL
			AND categories.id_categories IN(364,389,410,440,489,500,525,547)
			) cp ON cp.id_product = products.id_products 
			where products.id_products = ".$items['id']."
			GROUP BY products.id_products
							   ");
			$sub = $this->db->get()->row();
			$query = "";
			$query .= "(SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
			and categories.id_categories IN(364,389,410,440,489,500,525,547) )";
		
			$query .= "UNION all";
	
			$query .= "(
			SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
				and categories.parent IN(364,389,410,440,489,500,525,547) 
			)";
		
			$query .= "UNION all";
	
			$query .= "(SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
			and categories.parent IN(
				SELECT id_categories 
				FROM categories 
				where categories.parent IN(364,389,410,440,489,500,525,547)
			) )"; 
	
		
			$cat = $this->db->query($query)->result();
			
			$this->db->select("* from shipment_method
			where id = '".$items["chosen_shipping_id"]."'
			");
			$shipment = $this->db->get()->row();
			$ecomm_id_arr["id"] = $sub->code;
			$ecomm_id_arr["name"] = $items["name"];
			$ecomm_id_arr["price"] = $items["subtotal"];
			$ecomm_id_arr["brand"] = $sub->brnd;
			$ecomm_id_arr["category"] = $cat[0]->category.' - '.$cat[1]->category.' - '.$cat[2]->category;
			if(!empty($shipment->carrier)){
			$ecomm_id_arr["carrier"] = strtoupper($shipment->carrier);
			}else{
			$ecomm_id_arr["carrier"] = strtoupper($shipment->name);
			}
			$ecomm_id_arr["position"] = $i;
			$ecomm_id_arr["quantity"] = (int)$items["qty"];
			array_push($data_total['datalayer']['ecommerce']['checkout']['products'], $ecomm_id_arr);
			$i++;
		}

		echo json_encode($data_total);
	}


	public function ajax_set_point_rewards() {

		$voucherprice 		= $this->security->xss_clean($this->input->post('voucherprice'));
		$point 				= $this->security->xss_clean($this->input->post('point'));
		$id_customer 		= $this->security->xss_clean($this->input->post('id_customer'));
		$finalpoint_rewards	= 0;
		$alert				= '';

		//rein voucher point reward SESSION
		$this->session->set_userdata('sesspoint',$point);
		//rein voucher point reward SESSION

		if($this->session->userdata('customer')['customer_type'] == 'guest') {
			$alert = 'Silahkan Login untuk menggunakan point reward';
		}

		if($this->session->userdata('customer')['customer_type'] == 'regular') {
			/*hitung point rewards*/
			//get customer current point reward
			$this->db->select('current_pointreward')->from('customers')->where('id_customers', $id_customer);
			$current_point = $this->db->get()->row()->current_pointreward;

			if($point > $current_point){

				$alert = 'Point cannot bigger than '.$current_point;
				$this->session->unset_userdata('chosen_point');
				$this->session->unset_userdata('chosen_point_discount');

			} else{

				$this->db->select('*')->from('point_rewards')->where('id_point_rewards', 1);
				$point_rewards 		= $this->db->get()->row();
				$finalpoint_rewards = $point * (int) $point_rewards->conversion;

				$this->session->set_userdata('chosen_point', $point);
				$this->session->set_userdata('chosen_point_discount', $finalpoint_rewards);
			}
			/*hitung point rewards*/
		}

		/*hitung grand total include point rewards*/
		$total_item_amount = 0;
		foreach ($this->session->userdata('shipping_cart') as $rowid => $item) {
			$total_item_amount = $total_item_amount + $item['subtotal'];
		}

		if($finalpoint_rewards > $total_item_amount) {
			$alert = 'Jumlah Point Reward <br/>IDR '.number_format($finalpoint_rewards).'<br/> Melebihi Total Pembelian';
			$finalpoint_rewards	= 0;
		}

		/*new get final total shipping fee*/
		$this->load->helper('rajaongkir');
		$shipping_id_subdistrict = $this->input->post('subdistrict');
		$shipping_fee_array = array();
		foreach ($this->session->userdata('shipping_cart') as $item) {
			$shipping_fee_array[$item['chosen_shipping_id']][$item['warehouse_id']][] = $item;
		}

		//getcode 2hr & sameday
		$kode2hr = $this->db->select("*")->from("shipment_method")->where("id",'8')->get()->row()->id;
		$kodesame = $this->db->select("*")->from("shipment_method")->where("id",'9')->get()->row()->id;
		//getcode 2hr & sameday
		$final_total_shipping_fee = 0;
		$shipping_session = null;
		$shipping_session_index = 0;
		foreach ($shipping_fee_array as $warehouse_sid) {
			$total_fee_shipping = 0;
			foreach ($warehouse_sid as $item1) {
				$total_fee_warehouse 	= 0;
				$total_weight_wids		= 0;
				$count_wsid = count($item1);
				for($a=0;$a<$count_wsid;$a++){
					$this->db
					->select('dimension_weight, dimension_length, dimension_width, dimension_height')
					->from('products')
					->where('id_products', $item1[$a]['id']);
					$product_dimension	= $this->db->get()->row();
					$product_weight 	= $product_dimension->dimension_weight;	//gram
					$product_length 	= $product_dimension->dimension_length;	//cm
					$product_width 		= $product_dimension->dimension_width;	//cm
					$product_height 	= $product_dimension->dimension_height; //cm
					//check if volume is bigger than weight
					$volume_weight 		= $product_length * $product_width * $product_height / 6000; //kg
					if(($volume_weight * 1000) >= $product_weight) {
						$weight = $volume_weight * 1000;
					} else {
						$weight = $product_weight;
					}
					$total_weight_gram 	= ceil($weight * $item1[$a]['qty']); //gram
					$total_weight_wids	= $total_weight_wids + $total_weight_gram;
				}
				$shipping_session[$shipping_session_index]['warehouse_id'] 	= $item1[0]['warehouse_id'];

				if($item1[0]['chosen_shipping_id'] == $kode2hr) {
					// start 2hr delivery
					// $shipping_info = getAPI($shipping_id,$pinpoint,$custid,$productid,$warehouseid,$address,$price,$qty);
					// $shipping_info = getShippingExpress($kode2hr,$item1[0]['shipping_express'], $item1[0]['warehouse_id']);
					$shipping_info = getAPI($kode2hr,$this->session->userdata('guest_shipping_id')['pinpoin'],$this->session->userdata('customer')['customer_id'],$item1[0]['id'],$item1[0]['warehouse_id'],$this->session->userdata('guest_shipping_id')['address_form'],$item1[0]['price'],$item1[0]['qty'],$this->session->userdata('guest_shipping_id')['param_x']);
					
					$shipping_name = $shipping_info['shipping_name'];
					$total_shipping_fee = $shipping_info['total_shipping_fee'];
					// end 2hr delivery
				} else if($item1[0]['chosen_shipping_id'] == $kodesame){
					// start sameday delivery
					// $shipping_info = getShippingExpress($kodesame,$item1[0]['shipping_express'], $item1[0]['warehouse_id']);
					$shipping_info = getAPI($kodesame,$this->session->userdata('guest_shipping_id')['pinpoin'],$this->session->userdata('customer')['customer_id'],$item1[0]['id'],$item1[0]['warehouse_id'],$this->session->userdata('guest_shipping_id')['address_form'],$item1[0]['price'],$item1[0]['qty'],$this->session->userdata('guest_shipping_id')['param_x']);
					
					$shipping_name = $shipping_info['shipping_name'];
					$total_shipping_fee = $shipping_info['total_shipping_fee'];
					// end sameday delivery
				}else{
					//get shipping method name
					$this->db
					->select('name,shipper,carrier, service_code1, service_code2')
					->from('shipment_method')
					->where('id', $item1[0]['chosen_shipping_id']);
					$shipping_method 	= $this->db->get()->row();
					$shipping_name 		= $shipping_method->name;
					$shipping_carrier 	= $shipping_method->carrier;
					$shipping_shipper 	= $shipping_method->shipper;
					$service_code1 		= $shipping_method->service_code1;
					$service_code2 		= $shipping_method->service_code2;

					//get warehose sub district id
					$this->db->select('id_subdistrict')->from('warehouse')->where('id', $item1[0]['warehouse_id']);
					$warehouse_subdistrict_id = $this->db->get()->row()->id_subdistrict;
					$rajaongkir_cost = get_rajaongkir_ongkos($warehouse_subdistrict_id, $shipping_id_subdistrict, $total_weight_wids, $shipping_carrier);

					//check if weight is zero. If zero, then rajaongkir cannot proceed..
					if($total_weight_wids > 0) {
						//check which key has carrier name
						if($service_code2 != NULL) {
							if(count($rajaongkir_cost['rajaongkir']['results'][0]['costs']) != 0) {
								foreach($rajaongkir_cost['rajaongkir']['results'][0]['costs'] as $key => $result) {
									if($result['service'] == $service_code1 || $result['service'] == $service_code2) {
										$total_shipping_fee = $result['cost'][0]['value'];
										break;
									}
									else {
										$total_shipping_fee = 0; //service is not available
									}
								}
							}
							else {
								$total_shipping_fee = 0; //service is not available
							}
						}
						else {
							if(count($rajaongkir_cost['rajaongkir']['results'][0]['costs']) != 0) {
								foreach($rajaongkir_cost['rajaongkir']['results'][0]['costs'] as $key => $result) {
									if($result['service'] == $service_code1) {
										$total_shipping_fee = $result['cost'][0]['value'];
										break;
									}
									else {
										$total_shipping_fee = 0; //service is not available
									}
								}
							}
							else {
								$total_shipping_fee = 0; //service is not available
							}
						}
					}
					else {
						//total weight gram is zero
						$total_shipping_fee = 0; //service is not available
					}
				}

				$total_fee_warehouse 	= $total_fee_warehouse + $total_shipping_fee;
				$total_fee_shipping		= $total_fee_shipping + $total_fee_warehouse;

				$shipping_session[$shipping_session_index]['shipping_fee']	= $total_fee_warehouse;
				if($item1[0]['chosen_shipping_id'] == 5){
					$is_indent = "yes";
				}
				else{
					$is_indent = "no";
				}
				$shipping_session[$shipping_session_index]['is_indent'] 	= $is_indent;
				$shipping_session_index++;
			}
			$final_total_shipping_fee = $final_total_shipping_fee + $total_fee_shipping;

		}
		$this->session->set_userdata('shipping_session', $shipping_session);

		$this->session->set_userdata('total_shipping_fee', $final_total_shipping_fee);

		if($this->session->userdata('customer') && $this->session->userdata('customer')['customer_type'] == 'regular') {

			/*new get free fee shipping*/
			$this->load->helper('shipping');
			$free_shipping_fee 		= 0;
			$free_shipping_price 	= $this->db->select('free_shipping_type_subsidi')->from('configuration')->where('id_configuration',1)->get()->row()->free_shipping_type_subsidi;
			$free_shipping_type 	= $this->db->select('free_shipping_type')->from('configuration')->where('id_configuration',1)->get()->row()->free_shipping_type;
			if($free_shipping_type == 'region'){
				$selected_region_province = $this->db->select('province_id')->from('free_shipping_region')->where('configuration_id',1)->get()->result();
				foreach ($selected_region_province as $region_province) {
					if($region_province->province_id == $this->input->post('province')){
						if($free_shipping_price == 0){
							$free_shipping_fee = $final_total_shipping_fee;
						}
						else{
							$free_shipping_fee = $free_shipping_price;
						}
						break;
					}
				}
			}
			elseif($free_shipping_type == 'global'){
				$min_transaction = $this->db->select('min_transaction')->from('free_shipping_global')->where('configuration_id',1)->get()->row()->min_transaction;
				if($total_item_amount >= $min_transaction){
					if($free_shipping_price == 0){
						$free_shipping_fee = $final_total_shipping_fee;
					}
					else{
						$free_shipping_fee = $free_shipping_price;
					}
				}
				else{
					$free_shipping_fee = 0;
				}
			}
		}

		$this->session->set_userdata('free_shipping', $free_shipping_fee);

		$finalshippingfee = 0;
		$calculate_finalshippingfee = $final_total_shipping_fee - $free_shipping_fee;
		if($calculate_finalshippingfee > 0){
			$finalshippingfee = $calculate_finalshippingfee;
		}


		$first_total = 0;
		if(($total_item_amount - $voucherprice - $finalpoint_rewards) < 0 ){
			$first_total =  0;
		}else{
			$first_total = $total_item_amount - $voucherprice - $finalpoint_rewards;
		}

		$final_grand_total = 0;
		$grand_total = $first_total + $finalshippingfee;
		if($grand_total > 0 ){
			$final_grand_total = $grand_total;
		}
		/*hitung grand total include point rewards*/

		$first_total = 0;
		if(($total_item_amount - $voucherprice - $finalpoint_rewards) < 0 ){
			$first_total =  0;
		}else{
			$first_total = $total_item_amount - $voucherprice - $finalpoint_rewards;
		}

		/*if alert is null, is mean validation false*/
		if($alert != ''){
			$this->session->unset_userdata('sesspoint');
			$this->session->unset_userdata('chosen_point');
		}

		$data_total = array(
			'total_item_amount'			=> number_format($total_item_amount),
			'pointrewards'				=> number_format($finalpoint_rewards),
			'pointrewards_input'		=> $finalpoint_rewards,
			'alert'						=> $alert,
			'firsttotal'				=> number_format($first_total),
			'total_shipping_fee' 		=> number_format($final_total_shipping_fee),
			'total_free_shipping_fee' 	=> number_format($free_shipping_fee),
			'finalshippingfee' 			=> number_format($finalshippingfee),
			'grand_total' 				=> number_format($final_grand_total),
			//rein voucher point reward
			'sesspoint' => $this->session->userdata('sesspoint')
			//rein voucher point reward
		);

		// Data Layer
		$data_total['datalayer'] = array(
			'event' => 'EEcheckout'
		);
		$data_total['datalayer']['ecommerce']['checkout']['actionField'] = array(
			'step' => 2
		);
		$data_total['datalayer']['ecommerce']['checkout']['products'] = array();
		$i=1;
		foreach ($this->session->userdata('shipping_cart') as $items){
			$this->db->select(" products.title, products.product_code AS code, brands.brand AS brnd, cp.category AS cat,  products.alias as aliases , case when is_sale = 'yes' then discounted_price else sale_price end as price 
			from products
			join brands on brands.id_brands = products.brand_id
			left JOIN(
			SELECT * FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			and categories.parent IS NULL
			AND categories.id_categories IN(364,389,410,440,489,500,525,547)
			) cp ON cp.id_product = products.id_products 
			where products.id_products = ".$items['id']."
			GROUP BY products.id_products
							   ");
			$sub = $this->db->get()->row();

			$query = "";
			$query .= "(SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
			and categories.id_categories IN(364,389,410,440,489,500,525,547) )";
		
			$query .= "UNION all";
	
			$query .= "(
			SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
				and categories.parent IN(364,389,410,440,489,500,525,547) 
			)";
		
			$query .= "UNION all";
	
			$query .= "(SELECT id_category_product, id_product, id_category, id_categories, category, parent
			FROM category_product
			JOIN categories ON categories.id_categories = category_product.id_category
			WHERE id_product = ".$itemsp['id']." 
			and categories.parent IN(
				SELECT id_categories 
				FROM categories 
				where categories.parent IN(364,389,410,440,489,500,525,547)
			) )"; 
	
		
			$cat = $this->db->query($query)->result();

			
			$this->db->select("* from shipment_method
			where id = '".$items["chosen_shipping_id"]."'
			");
			$shipment = $this->db->get()->row();
			$ecomm_id_arr["id"] = $sub->code;
			$ecomm_id_arr["name"] = $items["name"];
			$ecomm_id_arr["price"] = $items["subtotal"];
			$ecomm_id_arr["brand"] = $sub->brnd; 
			$ecomm_id_arr["category"] = $cat[0]->category.' - '.$cat[1]->category.' - '.$cat[2]->category;
			if(!empty($shipment->carrier)){
			$ecomm_id_arr["carrier"] = strtoupper($shipment->carrier);
			}else{
			$ecomm_id_arr["carrier"] = strtoupper($shipment->name);
			}
			$ecomm_id_arr["position"] = $i;
			$ecomm_id_arr["quantity"] = (int)$items["qty"];
			array_push($data_total['datalayer']['ecommerce']['checkout']['products'], $ecomm_id_arr);
			$i++;
		}
		
		echo json_encode($data_total);
	}

	public function ajax_cek_current_qty() {
		$product_id = $this->security->xss_clean($this->input->post('product_id'));
		$a 			= $this->cart->contents();
		$b 			= 0;
		foreach ($a as $item) {
			if($item['id'] == $product_id){
				$b = $item['qty'];
			}
			break;
		}
		echo $b;
	}


	public function ajax_buy_again() {
		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$this->load->library('cart');
		$id_products = $this->input->post('product_id');
		$quantity = $this->input->post('qty');
		$status = 'true';

		foreach ($id_products as $n => $key) {
			// GET PRODUCT DATA
			$product = $this->product_m->get_product_buy_again($id_products[$n]);
			// echo "<pre>";
			// print_r($product);
			// echo "</pre>";
			if ($quantity[$n] == 0) {
				$status ='farmaku';
				break;
			}
			if ($product->is_backorder == 'no') {
				if ($product->stock == 0 || $product->stock < $quantity[$n]) {
					$status = 'false-'.ucwords($product->title);
					break;
				}
			}
			$data['id'] = (int) $id_products[$n];
			$data['name'] = ucwords($product->title);
			$data['qty'] = (int) $quantity[$n];
			$data['price'] = (int) $product->sale_price;

			$data['options']['warehouse_name'] = '';
			$data['options']['warehouse_id'] = NULL;

				//evaluate final price
				//check for flash sale
			if($this->session->has_userdata('flashsale_id_active')) {
					//if flash sale session is currently active
				$this->db->select('product_id, discounted_price')->from('flashsale_products')->where('flashsale_id',$this->session->userdata('flashsale_id_active'))->where('product_id',$data['id']);
				$flashsale_product = $this->db->get()->row();
			}
			if(count($flashsale_product) > 0) {
				$data['price'] = $flashsale_product->discounted_price;
			} else {
				if($product->is_sale == 'no') {
					$data['price'] = $product->sale_price;
				} else {
					$data['price'] = $product->discounted_price;
				}
			}
			$this->cart->product_name_rules = '[:print:]';
				//this is to eliminate cart product name restriction on special characters
			$this->cart->insert($data);
		}
		if ($status == 'true') {
			$this->load->helper('cart');
			$this->load->view('ajax/ajax_add_to_cart');
		}else{
			echo $status;
			exit();
		}
	}

	public function ajax_get_orderhistory_filter_date(){
		// echo "<pre>";
		// print_r($this->input->post());
		// echo "</pre>";
		$this->load->model('order_m');
		$id_customer = (int) $this->session->userdata('customer')['customer_id'];
		$start_date = $this->input->post('start');
		$last_date = $this->input->post('last');
		if ($start_date!= NULL || $last_date!=NULL) {
			$data['result_order_history'] = $this->order_m->order_history_filter_date($start_date,$last_date,$id_customer);
		}
		else{
			$data['limit'] = 1;
			$data['result_order_history'] = $this->order_m->get_order_history($id_customer);
		}
		$data['ts'] = $this->input->post('ts');
		if ($data['result_order_history'] != null) {
			$this->load->view('ajax/ajax_get_orderhistory_filter_date', $data);
		}
		else {
			?>
		<div class="row">
			<div class="col-12" style="padding: 15%">
				<center>
					<div id="wrapperLogo">
						<img id="LogoBW" src="<?= base_url() . 'uploads/logo1.png'; ?>" />
					</div>
					<h4 style="color:lightgrey;">TIDAK ADA PEMESANAN</h4>
				</center>
			</div>
		</div>
		<?php
		}

	}

	public function ajax_get_orderhistory_byorderid(){
		$this->load->model('order_m');
		$id_customer = (int) $this->session->userdata('customer')['customer_id'];
		$orderid = $this->input->post('orderid');
		// echo $orderid;
		if ($orderid!= NULL) {
			$data['result_order_history'] = $this->order_m->order_history_filter_orderid($orderid,$id_customer);
		}
		else{
			$data['limit'] = 1;
			$data['result_order_history'] = $this->order_m->get_order_history($id_customer);
		}
		$data['ts'] = $this->input->post('ts');
		if ($data['result_order_history'] != null) {
			$this->load->view('ajax/ajax_get_orderhistory_filter_date', $data);
		}
		else {
			?>
		<div class="row">
			<div class="col-12" style="padding: 15%">
				<center>
					<div id="wrapperLogo">
						<img id="LogoBW" src="<?= base_url() . 'uploads/logo1.png'; ?>" />
					</div>
					<h4 style="color:lightgrey;">TIDAK ADA PEMESANAN</h4>
				</center>
			</div>
		</div>
		<?php
		}
	}

	public function get_point(){
		$get_variable = $this->encrypt->decode($this->input->post('send'));

		$get_current_point = $this->customer_m->get_customer(json_decode($get_variable)->id)->current_pointreward;
		$subtotal = json_decode($get_variable)->subtotal;

		$voucher = $this->session->userdata('redeemed_voucher_amount');

		$subtotal_nett = $subtotal - $voucher;
		
		
		//get point rewards setting
		$this->db->select('*')->from('point_rewards')->where('id_point_rewards', 1);
		$point_conf = $this->db->get()->row()->conversion;

		$sisa = ($subtotal_nett / $point_conf) - $get_current_point;
		if($sisa > 0 ){
			$output = $get_current_point;
		}else{
			$output = ($subtotal_nett / $point_conf);
		}
		
		if($subtotal_nett < 0){
			$output = 0;
		}

		if($this->session->userdata('sesspoint')){
			echo $this->session->userdata('sesspoint');
		}else{
			echo floor($output);
		}

	}

	public function exit_point(){
		$output = "";
		$this->session->unset_userdata('sesspoint');
		echo $output;
	}

	public function exit_voucher(){
		$output = "";
		$this->session->unset_userdata('chosen_voucher_code');
		$this->session->unset_userdata('chosen_voucher_type');
		$this->session->unset_userdata('chosen_voucher_discount');
		$this->session->unset_userdata('total_categoryproduct_promo');
		$this->session->unset_userdata('total_brandproduct_promo');
		$this->session->unset_userdata('redeemed_voucher_amount');
		$this->session->unset_userdata('sessvoucher');
		echo $output;
	}

	public function set_modal_trigger(){
		$modal_trigger = 1;
		$user_ip = $this->input->ip_address();
		$this->db->select('*')->from('visitor_sessions')->where('ip_address', $user_ip);
		$visitor_session = $this->db->get()->result();
		$data = [
			'modal_trigger'=> $modal_trigger
		];
		$this->db->update('visitor_sessions',$data);
	}

	
	public function set_popup_trigger(){
		$popup_trigger = 1;
		$user_ip = $this->input->ip_address();
		$this->db->select('*')->from('visitor_sessions')->where('ip_address', $user_ip);
		$visitor_session = $this->db->get()->result();
		$data = [
			'popup_trigger'=> $popup_trigger
		];
		$this->db->update('visitor_sessions',$data);
	}

	public function ajax_get_polygon(){
		$id_polygon = $this->security->xss_clean($this->input->post('id_polygon'));
		// // $id_polygon = 5795;

		$this->db->select('polygon_area')->from('indonesia_polygonarea_subdistricts')->where('id_subdistrict', $id_polygon);

		$polygon_area 	= $this->db->get()->row()->polygon_area;
		echo $polygon_area;
	}

	function fetch()
	{
		$output = '';
		$data = $this->blog_m->fetch_data($this->input->post('limit'), $this->input->post('start'));
		if($data->num_rows() > 0)
		{
			foreach($data->result() as $row)
			{
				$output .= '
				<div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
				<div class="card p-2">
				<a href="'. base_url() . 'artikel/' . $row->alias.'">
				<img class="card-img-top" src="'.base_url() . 'uploads/blog/' . $row->image1.'" alt="Card image cap">
				</a>
				<div class="card-body">
				<h3>
				<a href="'. base_url() . 'artikel/' . $row->alias.'">'.$row->blog.'</a>
				</h3>
				<p style="margin-bottom: 10px;">
				<meta name="robots" content="noindex" />
				
				<i class="fas fa-calendar-alt"></i><a href="" style="text-decoration: none; color:#606975;">&nbsp;'.date('d M Y', strtotime($row->publish_date)).'&nbsp;</a> 
				<i class="fas fa-tags"></i>&nbsp;
				'.((!empty($row->tag1)) ? ''.$row->tag1.'' : '').'
				'.((!empty($row->tag2)) ? ''.$row->tag2.'' : '').'   
				
				</p>
				<p class="card-text">
				'.$content = (strlen($row->description) > 200 ? substr($row->description,0,200)."..." : $row->description ).'</p>
				<a href="'.base_url() . 'artikel/' . $row->alias.'" class="font-weight-bold">Lihat selengkapnya</a>
				</div>
				</div>
				</div>
				';
			}
		}
		echo $output;
	}

	function fetch_farmasi()
	{
		$output = '';
		$data = $this->category_m->fetch_data($this->input->post('limit'), $this->input->post('start'));
		if($data->num_rows() > 0)
		{
			foreach($data->result() as $row)
			{
				$output .= '
				<div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
				<a href="'.base_url('obat/category_list/'.$row->alias).'">
				<img src="'.base_url() . 'uploads/category/' . $row->thumbnail.'" alt="">
				</a>
				<h3>
				<a style="text-decoration:none; color:#374250" href="'.base_url('obat/category_list/'.$row->alias).'">
				'.ucfirst($row->category).'</a></h3>
				</div>
				<div class="col-1 hidden-md-down"></div>
				<meta name="robots" content="noindex" />
				';
			}
		}
		echo $output;
	}

	function fetch_promo()
	{
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}
	 $id_child = $this->input->post('id_child');
	 $min = (int)$this->input->post('min');
	 $max = (int)$this->input->post('max');
	 $rat = implode(',', $this->input->post('rat'));
	 $cat = implode(',', $this->input->post('cat'));
	 $brnd = implode(',', $this->input->post('brnd'));
	 $shpmnt = implode(',', $this->input->post('shpmnt'));
	 $sort = $this->input->post('sort');
	 $level = $this->input->post('level');

	 $this->load->library('pagination');
	 $config = array();
	 $config['base_url'] = '#';
	 $config['total_rows'] = $this->sale_m->count_all($id_child, (int)$min, (int)$max, (int)$rat, $cat,	$brnd, $shpmnt, $sort, $level);
	 $config['per_page'] = 32;
	 $config['uri_segment'] = 3; 
	 $config['use_page_numbers'] = TRUE;
	 $config['full_tag_open'] = '<ul class="pagination">';
	 $config['full_tag_close'] = '</ul>';
	 $config['first_tag_open'] = '<li>';
	 $config['first_tag_close'] = '</li>';
	 $config['prev_link'] = '<';
	 $config['prev_tag_open'] = '<li>';
	 $config['prev_tag_close'] = '</li>';
	 $config['next_link'] = '>';
	 $config['next_tag_open'] = '<li>';
	 $config['next_tag_close'] = '</li>';
	 $config['last_tag_open'] = '<li>';
	 $config['last_tag_close'] = '</li>';
	 $config['cur_tag_open'] = '<li class="link-active"><a class="font-active" href="">';
	 $config['cur_tag_close'] = '</a></li>';
	 $config['num_tag_open'] = '<li>';
	 $config['num_tag_close'] = '</li>';
	 $config['last_link'] = '>>';
	 $config['first_link'] = '<<';
	 $config['num_links'] = 5;
	 $this->pagination->initialize($config);
	 $url = $this->input->post('page');
	 if($url < 1){
		$page = 1;
	 }else{
		$page = $this->uri->segment(3);
	 }
	  $start = ($url - 1) * $config['per_page'];
	  foreach ($this->input->post('cat') as $tag){
	  $cat_name = $this->category_m->get_tag($tag)->category;
	  $data['category_sel'] .= '<span class="bubble '.$tag.'">'.$cat_name.'<i class="fa fa-times ml-2" onclick="deleteBubble('.$tag.')" aria-hidden="true"></i></span>';
	  }
	  $data['pagination_link'] = $this->pagination->create_links();
	  $data['product_list'] = $this->sale_m->get_all_promo_child(($config["per_page"]), $start, $id_child, (int)$min, (int)$max, (int)$rat, $cat, $brnd, $shpmnt, $sort, $level);
	  $data['fog'] = "<link rel='canonical' href='".base_url()."category/promo' />";
	  
	 $this->load->view('ajax/ajax_promo_products', $data);
	}
	
	public function ajax_get_shipping_districtv2() {
		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$shipping_province_id = (int) $this->input->post('id_shipping_province'); 
		$this->session->set_userdata(('guest_shipping_id')['shipping_id_province'], $shipping_province_id);

		//check districts table if province_id already available
		$count_districts = $this->shippingv2_m->get_district_ajax('id_indonesia_districts', 'indonesia_districts', $shipping_province_id, 'indonesia_id_province')->num_rows();

		if($count_districts > 0) {

			//districts already available, get the districts
			$data = $this->shippingv2_m->get_district_ajax('rajaongkir_id_district, district', 'indonesia_districts', $shipping_province_id, 'indonesia_id_province')->result();

		} else {
			//districts not available yet..then get rajaongkir data and store into districts table
			$this->load->helper('rajaongkir');
			//get list of districts from RajaOngkir.com API
			$districts = get_rajaongkir_data('city?province=' . $shipping_province_id); //get from helper file

			foreach($districts['rajaongkir']['results'] as $district) {

				//check first if rajaongkir district_id already exist..
				$count_districts = $this->shippingv2_m->get_district_ajax('rajaongkir_id_district', 'indonesia_districts', $district['city_id'], 'rajaongkir_id_district')->num_rows();

				if($count_districts == 0) {
					//can input new data, because still empty
					//insert into districts database
					$update = array(
						'rajaongkir_id_district' => $district['city_id'],
						'district' => $district['city_name'],
						'indonesia_id_province' => $shipping_province_id
					);
					$this->db->insert('indonesia_districts', $update);
				}
			}

			//districts should be available now, get the districts
			$data = $this->shippingv2_m->get_district_ajax('rajaongkir_id_district, district', 'indonesia_districts', $shipping_province_id, 'indonesia_id_province')->result();
		}

		$datacustomer_province = array(
			'shipping_id_province' => $shipping_province_id,
			'id_province' => $shipping_province_id
		);


		echo json_encode($data);
	}

	public function ajax_get_shipping_subdistrictv2() { 

		//test if ajax call to prevent direct access
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}

		$district_id = (int) $this->input->post('id_shipping_district');

		//check subdistricts table if district_id already available 
		$count_subdistricts = $this->shippingv2_m->get_district_ajax('id_indonesia_subdistricts', 'indonesia_subdistricts', $district_id, 'indonesia_id_district')->num_rows();

		if($count_subdistricts > 0) {

			//subdistricts already available, get the subdistricts 
			$data = $this->shippingv2_m->get_district_ajax('rajaongkir_id_subdistrict, subdistrict', 'indonesia_subdistricts', $district_id, 'indonesia_id_district')->result();
		} else {
			//subdistricts not available yet..then get rajaongkir data and store into subdistricts table
			$this->load->helper('rajaongkir');
			//get list of subdistricts from RajaOngkir.com API
			$subdistricts = get_rajaongkir_data('subdistrict?city=' . $district_id); //get from helper file

			foreach($subdistricts['rajaongkir']['results'] as $subdistrict) {

				//check first if rajaongkir subdistrict_id already exist..
				$count_subdistricts = $this->shippingv2_m->get_district_ajax('rajaongkir_id_subdistrict', 'indonesia_subdistricts', $subdistrict['subdistrict_id'], 'rajaongkir_id_subdistrict')->num_rows();

				if($count_subdistricts == 0) {
					//can input new data, because still empty
					//insert into subdistricts database
					$update = array(
						'rajaongkir_id_subdistrict' => $subdistrict['subdistrict_id'],
						'subdistrict' => $subdistrict['subdistrict_name'],
						'indonesia_id_district' => $district_id
					);
					$this->db->insert('indonesia_subdistricts', $update);
				}
			}

			//subdistricts should be available now, get the subdistricts
			$data = $this->shippingv2_m->get_district_ajax('rajaongkir_id_subdistrict, subdistrict', 'indonesia_subdistricts', $district_id, 'indonesia_id_district')->result();
		}

		echo json_encode($data);
	}
}
