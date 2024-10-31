<?php
/**
 * Base Api Class
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/mwb-crm-fw/api
 */

/**
 * Base Api Class.
 *
 * This class defines all code necessary api communication.
 *
 * @since      1.0.0
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/mwb-crm-fw/api
 */
class Mwb_Cf7_Integration_Engagebay_Api extends Mwb_Cf7_Integration_Engagebay_Api_Base {

	/**
	 * Engagebay API key
	 *
	 * @var     string  api key
	 * @since   1.0.0
	 */
	private static $api_key;

	/**
	 * Engagebay API url
	 *
	 * @var     string  api url
	 * @since   1.0.0
	 */
	private static $api_url = 'https://app.engagebay.com/dev/api/';

	/**
	 * Instance of the class.
	 *
	 * @var     object  $instance  Instance of the class.
	 * @since   1.0.0
	 */
	protected static $instance = null;

	/**
	 * Main Mwb_Cf7_Integration_Engagebay_Api_Base Instance.
	 *
	 * Ensures only one instance of Mwb_Cf7_Integration_Engagebay_Api_Base is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @return Mwb_Cf7_Integration_Engagebay_Api_Base - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		self::initialize();
		return self::$instance;
	}

	/**
	 * Initialize properties.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $token_data Saved token data.
	 */
	private static function initialize( $token_data = array() ) {
		self::$api_key = get_option( 'mwb-cf7-' . Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '-api-key', '' );
	}

	/**
	 * Get api domain.
	 *
	 * @since 1.0.0
	 *
	 * @return string Site redirecrt Uri.
	 */
	public function get_redirect_uri() {
		return admin_url();
	}

	/**
	 * Get Api key.
	 *
	 * @since 1.0.0
	 *
	 * @return string Api key.
	 */
	public function get_api_key() {
		return ! empty( self::$api_key ) ? self::$api_key : false;
	}

	/**
	 * Get Request headers.
	 *
	 * @param string $method Request method.
	 * @return array headers.
	 */
	public function get_auth_header( $method = '' ) {
		$authorization_key = self::$api_key;
		if ( ! empty( $method ) && 'get' === $method ) {
			$headers = array(
				'Authorization' => $authorization_key,
				'Accept'        => 'application/json',
			);
		} else {
			$headers = array(
				'Authorization' => $authorization_key,
				'Content-Type'  => 'application/json',
				'Accept'        => 'application/json',
			);
		}
		return $headers;
	}

	/**
	 * Get all module data.
	 *
	 * @param  boolean $force Fetch from api.
	 * @return array          Module data.
	 */
	public function get_modules_data( $force = false ) {

		return $this->get_modules();
	}

	/**
	 * Perform cf7 sync.
	 *
	 * @param    string $object      CRM object name.
	 * @param    array  $record_data Request data.
	 * @param    array  $log_data    Data to create log.
	 * @param    bool   $manual_sync If synced manually.
	 * @since    1.0.0
	 * @return   array
	 */
	public function perform_form_sync( $object, $record_data, $log_data = array(), $manual_sync = false ) {

		$result = array(
			'succes' => false,
			'msg'    => __( 'Something went wrong', 'mwb-cf7-integration-with-engagebay' ),
		);

		$feed_id            = ! empty( $log_data['feed_id'] ) ? $log_data['feed_id'] : false;
		$log_data['crm_id'] = $this->maybe_update_object( $object, $feed_id, $record_data );
		if ( isset( $log_data['crm_id'] ) && ! empty( $log_data['crm_id'] ) ) {

			// Send a update request.
			$result = $this->update_single_record(
				$feed_id,
				$object,
				$record_data,
				false,
				$log_data
			);

		} else {
			$result = $this->handle_single_record(
				'post',
				$object,
				$record_data,
				false,
				$log_data
			);
		}

		return $result;
	}

	/**
	 * Check if record already exists or not
	 *
	 * @param    string $record_type       CRM object.
	 * @param    string $feed_id           Feed ID.
	 * @param    array  $request_data      Request data.
	 * @since    1.0.0
	 * @return   bool
	 */
	public function maybe_update_object( $record_type, $feed_id, $request_data ) {

		$result = false;
		if ( ! empty( $feed_id ) ) {
			$duplicate_check_fields = get_post_meta( $feed_id, 'mwb-' . Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '-cf7-primary-field', true );
			$primary_field          = ! empty( $duplicate_check_fields ) ? $duplicate_check_fields : false;
		}

		if ( $primary_field ) {
			$search_response = $this->search_record( $record_type, $primary_field, $request_data[ $primary_field ] );
		}

		$result = ! empty( $search_response ) ? $search_response : false;
		return $result;
	}

	/**
	 * Get Objects from local options or from quickbooks
	 *
	 * @return array
	 */
	public function get_modules() {

		$objects = apply_filters(
			'mwb_cf7_' . Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '_objects_list',
			array(
				'Contacts'  => 'Contacts',
				'Companies' => 'Companies',
				'Deals'     => 'Deals',
				'Tasks'     => 'Tasks',
			)
		);

		return $objects;
	}

	/**
	 * Create single record on CRM.
	 *
	 * @param  string  $action      API action.
	 * @param  string  $module      CRM Module name.
	 * @param  array   $record_data Request data.
	 * @param  boolean $is_bulk     Is a bulk request.
	 * @param  array   $log_data    Data to create log.
	 *
	 * @since 1.0.0
	 *
	 * @return array Response data.
	 */
	public function handle_single_record( $action = 'get', $module, $record_data, $is_bulk = false, $log_data = array() ) {
		$data = array();

		// Remove empty values.
		if ( 'post' === $action ) {
			$response = $this->create_or_update_record( $module, $record_data, $is_bulk, $log_data, 'create' );
		} else {
			$response = $this->get_record( $module, $record_data, $is_bulk, $log_data );
		}

		if ( $this->is_success( $response ) ) {
			if ( isset( $response['data'] ) ) {
				$response['data'] = json_decode( $response['data'], ARRAY_A );
				if ( ! empty( $response['company-updated'] ) ) {
					$response['data']['mwb-event'] = 'Update';
				} else {
					$response['data']['mwb-event'] = 'Create';
				}
			}

			$data = $response['data'];
		} else {
			$data = $response;
		}

		return $data;
	}

	/**
	 * Update single record on CRM.
	 *
	 * @param  string $feed_id     CRM Module feed id.
	 * @param  string $module      CRM Module name.
	 * @param  array  $record_data Request data.
	 * @param  bool   $is_bulk     Is bulk request.
	 * @param  array  $log_data    Data to create log.
	 *
	 * @since 1.0.0
	 *
	 * @return array Response data.
	 */
	public function update_single_record( $feed_id, $module, $record_data, $is_bulk = false, $log_data = array() ) {

		$data = array();

		// Remove empty values.
		if ( is_array( $record_data ) ) {
			$record_data = array_filter( $record_data );
		}

		$response = $this->create_or_update_record( $module, $record_data, $is_bulk, $log_data, true );

		if ( $this->is_success( $response ) ) {
			$response['data'] = json_decode( $response['data'], ARRAY_A );
			$response['data']['mwb-event'] = 'Update';

			$data = $response['data'];

		} else {
			$data = $response;
		}

		return $data;
	}

	/**
	 * Create batch record on Engagebay
	 *
	 * @param  array $record_data Request data.
	 * @param  array $log_data    Data to create log.
	 *
	 * @since 1.0.0
	 *
	 * @return array Response data.
	 */
	public function create_batch_record( $record_data, $log_data = array() ) {

		$data = array();
		// Remove empty values.
		if ( is_array( $record_data ) ) {
			$record_data = array_filter( $record_data );
		}

		$response = $this->create_or_update_record( 'batch', $record_data, true, $log_data );
		if ( $this->is_success( $response ) ) {
			$response['data'] = json_decode( $response['data'], ARRAY_A );
			$data = $response['data'];
		} else {
			$data = $response;
		}
		return $response;
	}

	/**
	 * Check if resposne has success code.
	 *
	 * @param  array $response  Response data.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean true|false.
	 */
	private function is_success( $response ) {
		if ( ! empty( $response['code'] ) ) {
			return in_array( $response['code'], array( '200', '201', '204', '202', 200, 201, 204, 202 ), true );
		}
		return true;
	}

	/**
	 * Create of update record data.
	 *
	 * @param  string  $module     Module name.
	 * @param  array   $record_data Module data.
	 * @param  boolean $is_bulk    Is a bulk request.
	 * @param  array   $log_data   Data to create log.
	 * @param  bool    $is_update  Is update request or not.
	 *
	 * @return array               Response data.
	 */
	private function create_or_update_record( $module, $record_data, $is_bulk, $log_data, $is_update = false ) {

		if ( empty( $module ) || empty( $record_data ) ) {
			return;
		}
		$this->base_url = self::$api_url;
		$headers        = $this->get_auth_header();
		$sync_data      = array();
		$crm_id         = ! empty( $log_data['crm_id'] ) ? $log_data['crm_id'] : '';
		unset( $log_data['crm_id'] );
		$form_name = '';
		if ( isset( $record_data['form_name'] ) ) {
			$form_name = $record_data['form_name'];
			unset( $record_data['form_name'] );
		}
		if ( true === $is_update ) {
			if ( 'Contacts' === $module ) {
				$endpoint = 'panel/subscribers/update-partial';
				foreach ( $record_data as $name => $value ) {
					$sync_data[] = array(
						'name'  => $name,
						'value' => $value['value'],
					);
				}
				$contact_data = array(
					'id'         => $crm_id,
					'properties' => $sync_data,
					'tags'       => array(
						array( 'tag' => $form_name ),
					),
				);

				$request_data = wp_json_encode( $contact_data );
				$response     = $this->put( $endpoint, $request_data, $headers );
			} elseif ( 'Companies' === $module ) {
				$endpoint = 'panel/companies/update-partial';
				foreach ( $record_data as $name => $value ) {
					$sync_data[] = array(
						'name'  => $name,
						'value' => $value['value'],
					);
				}
				$contact_data = array(
					'id'         => $crm_id,
					'properties' => $sync_data,
					'tags'       => array(
						array( 'tag' => $form_name ),
					),
				);
				$request_data = wp_json_encode( $contact_data );
				$response     = $this->put( $endpoint, $request_data, $headers );
			}
		} else {
			if ( 'Contacts' === $module ) {
				$endpoint = 'panel/subscribers/subscriber';
				if ( empty( $record_data['email']['value'] ) ) {
					$response = array(
						'code'    => 400,
						'message' => 'Bad Request',
						'data'    => 'Email field is empty.',
					);
				} else {
					foreach ( $record_data as $name => $value ) {
						$sync_data[] = array(
							'name'  => $name,
							'value' => $value['value'],
						);
					}
					$contact_data = array(
						'properties' => $sync_data,
						'tags'       => array(
							array( 'tag' => $form_name ),
						),
					);
					$request_data = wp_json_encode( $contact_data );
					$response     = $this->post( $endpoint, $request_data, $headers );
				}
			} elseif ( 'Deals' === $module ) {
				$endpoint   = 'panel/deals/deal';
				$properties = array();

				if ( empty( $record_data['name']['value'] ) ) {
					$response = array(
						'code'    => 400,
						'message' => 'Bad Request',
						'data'    => 'Deal name field is empty.',
					);
				} else {

					foreach ( $record_data as $name => $value ) {
						if ( $value['is_custom'] == 'yes' ) {
							$properties[] = array(
								'name'  => $name,
								'value' => $value['value'],
							);
						} else {
							$sync_data[ $name ] = $value['value'];
						}
					}
					$sync_data['properties'] = $properties;
					$sync_data['tags']       = array(
						array( 'tag' => $form_name ),
					);
					$request_data            = wp_json_encode( $sync_data );
					$response                = $this->post( $endpoint, $request_data, $headers );
				}
			} elseif ( 'Companies' === $module ) {
				$endpoint = 'panel/companies/company';
				if ( empty( $record_data['name']['value'] ) ) {
					$response = array(
						'code'    => 400,
						'message' => 'Bad Request',
						'data'    => 'Company name field is empty.',
					);
				} else {
					foreach ( $record_data as $name => $value ) {
						$sync_data[] = array(
							'name'  => $name,
							'value' => $value['value'],
						);
					}
					$contact_data = array(
						'properties' => $sync_data,
						'tags'       => array(
							array( 'tag' => $form_name ),
						),
					);

					$request_data = wp_json_encode( $contact_data );
					$response     = $this->post( $endpoint, $request_data, $headers );
					if ( ! $this->is_success( $response ) ) {
						if ( 400 === $response['code'] ) {
							if ( false !== strpos( $response['data'], 'already exists' ) ) {
								$get_header   = $this->get_auth_header( 'get' );
								$params       = array();
								$company_name = '';
								foreach ( $record_data as $name => $value ) {
									if ( 'name' === $name ) {
										$company_name = $value['value'];
										break;
									}
								}
								$get_id = 0;
								$get_data = $this->get( 'search?q=' . $company_name . '&type=Company', $params, $get_header );
								if ( $this->is_success( $get_data ) ) {
									$response_data = json_decode( $get_data['data'], ARRAY_A );
									$count         = $response_data[0]['count'];
									if ( 1 === $count ) {
										if ( ( $response_data[0]['name'] == $company_name ) || ( $response_data[0]['name_sort'] == strtolower( $company_name ) ) ) {
											$get_id = $response_data[0]['id'];
										}
									} else {
										foreach ( $response_data as $companies ) {
											if ( ( $companies['name'] == $company_name ) || ( $companies['name_sort'] == strtolower( $company_name ) ) ) {
												$get_id = $companies['id'];
											}
										}
									}
									if ( ! empty( $get_id ) ) {
										$contact_data = array(
											'id'         => $get_id,
											'properties' => $sync_data,
											'tags'       => array(
												array( 'tag' => $form_name ),
											),
										);
										$body         = wp_json_encode( $contact_data );
										$is_update    = true;
										$response     = $this->put( 'panel/companies/update-partial', $body, $headers );
										if ( ! empty( $response ) && is_array( $response ) ) {
											$response['company-updated'] = 'updated';
										}
									} else {
										$contact_data = array(
											'properties' => $sync_data,
											'tags'       => array(
												array( 'tag' => $form_name ),
											),
										);
										$request_data = wp_json_encode( $contact_data );
										$response     = $this->post( $endpoint, $request_data, $headers );
									}
								}
							}
						}
					}
				}
			} elseif ( 'Tasks' === $module ) {
				$endpoint = 'panel/tasks';

				if ( empty( $record_data['name']['value'] ) ) {
					$response = array(
						'code'    => 400,
						'message' => 'Bad Request',
						'data'    => 'Task name field is empty.',
					);
				} else {
					foreach ( $record_data as $name => $value ) {
						$sync_data[ $name ] = $value['value'];
					}
					$request_data = wp_json_encode( $sync_data );
					$response     = $this->post( $endpoint, $request_data, $headers );
				}
			}
		}

		$this->log_request_in_db( __FUNCTION__, $module, $record_data, $response, $log_data, $is_update );

		return $response;

	}

	/**
	 * Update record data.
	 *
	 * @param    string $module           Module name.
	 * @param    array  $record_data      Module request.
	 * @param    array  $module_id        Module id.
	 * @param    array  $log_data         Data to create log.
	 * @since    1.0.0
	 * @return   array                    Response data.
	 */
	private function get_record( $module, $record_data = false, $module_id = false, $log_data = array() ) {

		if ( empty( $module ) ) {
			return false;
		}

		$this->base_url = self::$api_url;
		$headers        = $this->get_auth_header();
		$endpoint       = '/v3.1/' . $module;

		if ( ! empty( $module_id ) ) {
			$module .= '/' . $module_id;
		}
		$response = $this->get( $endpoint, array(), $headers );

		if ( ! empty( $log_data ) ) {
			$this->log_request_in_db( __FUNCTION__, $module, $record_data, $response, $log_data );
		}

		return $response;
	}

	/**
	 * Search record data without Id.
	 *
	 * @since 1.0.0
	 * @param string $module        Module name.
	 * @param string $primary_field Primary field.
	 * @param string $request_data  Requested data.
	 * @return string
	 */
	public function search_record( $module, $primary_field, $request_data ) {
		if ( empty( $module ) ) {
			return;
		}
		$record_id = '';
		// This GET Request is a query or a CRUD operation.
		$this->base_url = self::$api_url;
		$headers        = $this->get_auth_header( 'get' );
		if ( 'Contacts' === $module ) {
			$endpoint    = 'panel/subscribers/contact-by-email/' . $request_data['value'];
			$get_contact = $this->get( $endpoint, array(), $headers );
			if ( $this->is_success( $get_contact ) ) {
				$get_contact['data'] = json_decode( $get_contact['data'], ARRAY_A );
				$response_data       = $get_contact['data'];
				$record_id           = $response_data['id'];
			}
		}

		return $record_id;
	}

	/**
	 * Get the request structured.
	 *
	 * @since 1.0.0
	 *
	 * @param array $record_data The request.
	 *
	 * @return array
	 */
	public static function format_request_structure( $record_data = array() ) {
		foreach ( $record_data as $key => $value ) {
			if ( ! empty( strpos( $key, '__' ) ) ) {
				$array_assoc = explode( '__', $key );
				$array_assoc = array_reverse( $array_assoc );

				$result_array = array();
				$temp_value   = array();

				foreach ( $array_assoc as $k => $single_key ) {
					$temp_value = array(
						$single_key => ! empty( $temp_value ) ? $temp_value : $value,
					);

					$result_array = $temp_value;
				}

				if ( ! empty( $record_data[ $key ] ) ) {
					unset( $record_data[ $key ] );
				}

				if ( ! empty( $result_array ) ) {
					// Same key already resolved. May be billing/shipping one.
					if ( ! empty( $record_data[ key( $result_array ) ] ) ) {
						$duplicate_key      = key( $result_array );
						$existing_value     = $record_data[ $duplicate_key ];
						$new_resolved_value = $result_array[ $duplicate_key ];

						// Resolved_value.
						$record_data[ $duplicate_key ] = array_merge( $existing_value, $new_resolved_value );
					} else {
						$record_data = array_merge( $record_data, $result_array );
					}
				}
			}
		}

		return $record_data;
	}

	/**
	 * Get the Engagebay api request format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $module The module of crm.
	 *
	 * @return string|bool The request json for module.
	 */
	public function get_module_request( $module = false ) {

		$headers        = $this->get_auth_header( 'get' );
		$this->base_url = self::$api_url;
		$params         = array();
		$object_fields  = array();
		if ( 'Contacts' === $module ) {
			$object_fields = array(
				'email'     => array(
					'field_name'    => 'email',
					'field_label'   => 'Email',
					'field_type'    => 'TEXT',
					'is_required'   => true,
					'primary_field' => 'email',
				),
				'name'      => array(
					'field_name'  => 'name',
					'field_label' => 'First Name',
					'field_type'  => 'TEXT',
					'is_required' => false,
				),
				'last_name' => array(
					'field_name'  => 'last_name',
					'field_label' => 'Last Name',
					'field_type'  => 'TEXT',
					'is_required' => false,
				),
				'phone'     => array(
					'field_name'  => 'phone',
					'field_label' => 'Phone Number',
					'field_type'  => 'TEXT',
					'is_required' => false,
				),
				'role'      => array(
					'field_name'  => 'role',
					'field_label' => 'Role',
					'field_type'  => 'TEXT',
					'is_required' => false,
				),
				'website'   => array(
					'field_name'  => 'website',
					'field_label' => 'Website',
					'field_type'  => 'TEXT',
					'is_required' => false,
				),
			);
		} elseif ( 'Deals' === $module ) {
			$object_fields = array(
				'name'               => array(
					'field_name'  => 'name',
					'field_label' => 'Deal Name',
					'field_type'  => 'TEXT',
					'is_required' => true,
				),
				'unique_id'          => array(
					'field_name'  => 'unique_id',
					'field_label' => 'Deal ID',
					'field_type'  => 'NUMBER',
					'is_required' => false,
				),
				'description'        => array(
					'field_name'  => 'description',
					'field_label' => 'Description',
					'field_type'  => 'TEXTAREA',
					'is_required' => false,
				),
				'milestoneLabelName' => array(
					'field_name'  => 'milestoneLabelName',
					'field_label' => 'Milestone',
					'field_type'  => 'LIST',
					'is_required' => true,
					'field_data'  => 'New, Prospect, Proposal, Won, Lost',
				),
				'amount'             => array(
					'field_name'  => 'amount',
					'field_label' => 'Amount',
					'field_type'  => 'NUMBER',
					'is_required' => false,
				),
				'closed_date'        => array(
					'field_name'  => 'closed_date',
					'field_label' => 'Close Date',
					'field_type'  => 'DATE',
					'is_required' => false,
				),
			);
		} elseif ( 'Companies' === $module ) {
			$object_fields = array(
				'name'  => array(
					'field_name'  => 'name',
					'field_label' => 'Company Name',
					'field_type'  => 'TEXT',
					'is_required' => true,
				),
				'url'   => array(
					'field_name'  => 'url',
					'field_label' => 'Company Domain (URL)',
					'field_type'  => 'TEXT',
					'is_required' => false,
				),
				'email' => array(
					'field_name'  => 'email',
					'field_label' => 'Company Email',
					'field_type'  => 'TEXT',
					'is_required' => false,
				),
				'phone' => array(
					'field_name'  => 'phone',
					'field_label' => 'Phone Number',
					'field_type'  => 'TEXT',
					'is_required' => false,
				),
			);
		} elseif ( 'Tasks' === $module ) {
			$object_fields = array(
				'name'           => array(
					'field_name'  => 'name',
					'field_label' => 'Name',
					'field_type'  => 'TEXT',
					'is_required' => true,
				),
				'description'    => array(
					'field_name'  => 'description',
					'field_label' => 'Details',
					'field_type'  => 'TEXTAREA',
					'is_required' => false,
				),
				'type'           => array(
					'field_name'  => 'type',
					'field_label' => 'Type',
					'field_type'  => 'LIST',
					'is_required' => false,
					'field_data'  => 'TODO, EMAIL, CALL',
				),
				'closed_date'    => array(
					'field_name'  => 'closed_date',
					'field_label' => 'Due Date',
					'field_type'  => 'DATE',
					'is_required' => false,
				),
				'task_milestone' => array(
					'field_name'  => 'task_milestone',
					'field_label' => 'Status',
					'field_type'  => 'LIST',
					'is_required' => false,
					'field_data'  => 'not_started, in_progress, waiting, completed, deferred',
				),
				'task_priority'  => array(
					'field_name'  => 'task_priority',
					'field_label' => 'Priority',
					'field_type'  => 'LIST',
					'is_required' => false,
					'field_data'  => 'HIGH, MEDIUM, LOW',
				),
			);
		}
		return $object_fields;
	}

	/**
	 * Returns custom fields for Engagebay object.
	 *
	 * @since 1.0.0
	 * @param array $fields custom fields.
	 * @param array $object_fields object fields.
	 * @return array
	 */
	public function get_custom_fields( $fields, $object_fields ) {
		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field ) {
				$field_name                   = $field['field_name'];
				$object_fields[ $field_name ] = array(
					'field_name'   => $field_name,
					'field_label'  => $field['field_label'],
					'field_type'   => $field['field_type'],
					'is_required'  => false,
					'custom_field' => 'yes',
				);
				if ( 'LIST' === $field['field_type'] || 'CHECKBOX' === $field['field_type'] || 'MULTICHECKBOX' === $field['field_type'] || 'FILE' === $field['field_type'] || 'TEXTAREA' === $field['field_type'] ) {
					if ( ! empty( $field['field_data'] ) ) {
						$field_data = $field['field_data'];
					} else {
						$field_data = '';
					}
					$object_fields[ $field_name ]['field_data'] = $field_data;
				}
			}
		}
		return $object_fields;
	}

	/**
	 * Get fields from engagebay.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $object The request object type.
	 * @param  bool   $is_force The request object type.
	 *
	 * @return array  CRM fields w.r.t. API.
	 */
	public function get_module_fields( $object, $is_force = false ) {

		$module_json = $this->get_module_request( $object );
		if ( empty( $module_json ) ) {
			$arr = array();
		} else {
			$arr = $module_json;
		}

		if ( empty( $arr ) ) {
			return array();
		}

		return $arr;
	}

	/**
	 * Get test format for quickbooks request.
	 *
	 * @param    string $module   Data to get.
	 * @since    1.0.0
	 * @return   array.
	 */
	public function get_test_request( $module = 'Users' ) {
		return $this->get_record( $module );
	}

	/**
	 * Get Owner from all users for quickbooks request.
	 *
	 * @since 1.0.0
	 *
	 * @return array.
	 */
	public function get_owner_account() {
		$_account = '';
		$auth_key = get_option( 'mwb-cf7-' . Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '-api-key', '' );
		$headers  = array(
			'Authorization' => $auth_key,
			'Accept'        => 'application/json',
		);
		$endpoint = 'panel/users/profile/user-info';

		$this->base_url = self::$api_url;

		$params = array();

		$response      = $this->get( $endpoint, $params, $headers );
		$response_code = isset( $response['code'] ) ? $response['code'] : '';
		if ( 200 === $response_code ) {
			$result = ! empty( $response['data'] ) ? json_decode( $response['data'], ARRAY_A ) : array();
			foreach ( $result as $key => $account ) {
				if ( 'name' === $key ) {
					$_account = $account;
					break;
				}
			}
		}

		update_option( 'mwb-cf7-' . Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '-owner-account', $_account );
		return $_account;
	}

	/**
	 * Get Owner from all users for quickbooks request.
	 *
	 * @param bool $force fetch from api or not.
	 * @since 1.0.0
	 *
	 * @return array.
	 */
	public function get_users_account( $force = false ) {

		if ( true === $force ) {
			$response = $this->get_test_request();

			$response_code = isset( $response['code'] ) ? $response['code'] : '';
			if ( 200 === $response_code ) {
				$result = ! empty( $response['data'] ) ? $response['data'] : array();
				foreach ( $result as $key => $account ) {
					$_account[ $account['USER_ID'] ] = $account['EMAIL_ADDRESS'];
				}
			} else {
				$_account = array();
			}
		} else {
			$_account = get_option( 'mwb-cf7-' . Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '-user-accounts', array() );

			if ( empty( $_account ) ) {
				$this->get_users_account( true );
			}
		}

		return ! empty( $_account ) ? $_account : array();
	}

	/**
	 * Return authentication response.
	 *
	 * @since 1.0.0
	 * @param string $auth_key api key.
	 * @return array
	 */
	public function perform_auth_trial( $auth_key ) {
		$headers  = array(
			'Authorization' => $auth_key,
			'Accept'        => 'application/json',
		);
		$endpoint = 'panel/users/profile/user-info';

		$this->base_url = self::$api_url;

		$params = array();

		$response = $this->get( $endpoint, $params, $headers );
		if ( is_wp_error( $response ) ) {
			$message = array(
				'status' => false,
				'code'   => 400,
				'msg'    => __(
					'An unexpected error occurred. Please try again.',
					'mwb-cf7-integration-with-engagebay'
				),
			);
		} else {
			$response_code = isset( $response['code'] ) ? $response['code'] : '';
			$response_body = isset( $response['data'] ) ? json_decode( $response['data'], ARRAY_A ) : '';
			if ( isset( $response_body ) && 200 === $response_code ) {
				if ( ! empty( $response_body ) ) {
					$message = array(
						'status' => true,
						'code'   => 200,
						'data'   => $response_body,
					);
				}
			} else {
				$message = array(
					'status' => false,
					'code'   => $response_code,
					'data'   => $response_body,
				);
			}
		}
		return $message;
	}


	/**
	 * Log request and response in database.
	 *
	 * @param  string $event       Event of which data is synced.
	 * @param  string $crm_object  Update or create crm object.
	 * @param  array  $request     Request data.
	 * @param  array  $response    Api response.
	 * @param  array  $log_data    Extra data to be logged.
	 * @param  bool   $is_update    is update request.
	 */
	private function log_request_in_db( $event, $crm_object, $request, $response, $log_data, $is_update = false ) {

		$feed    = ! empty( $log_data['feed_name'] ) ? $log_data['feed_name'] : false;
		$feed_id = ! empty( $log_data['feed_id'] ) ? $log_data['feed_id'] : false;
		$event   = ! empty( $event ) ? $event : false;

		$engagebay_object = $crm_object;
		$engagebay_id     = $this->get_object_id_from_response( $response, $engagebay_object );
		$is_success       = $this->is_success_response( $response );
		if ( is_array( $request ) && ! empty( $request ) ) {
			foreach ( $request as $name => $value ) {
				$request[ $name ] = $value['value'];
			}
		}
		$request          = serialize( $request ); //phpcs:ignore
		if ( ! empty( $response['data'] ) ) {
			$response['data'] = json_decode( $response['data'], ARRAY_A );
		}
		if ( ! empty( $response['company-updated'] ) ) {
			unset( $response['company-updated'] );
		}
		$response         = serialize( $response ); //phpcs:ignore
		$time             = time();

		switch ( $is_update ) {
			case true === $is_update:
				$operation = 'Update';
				break;

			case 'search':
				$operation = 'Search';
				break;

			case 'create':
			default:
				$operation = 'Create';
				break;
		}

		$log_data = array(
			'event'    => $event,
			'feed_id'  => $feed_id,
			'feed'     => $feed,
			'request'  => $request,
			'response' => $response,
			Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '_id' => $engagebay_id,
			Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '_object' => $engagebay_object . ' - ' . $operation,
			'time'     => time(),
		);

		// Structure them!
		$this->insert_log_data( $log_data );
	}

	/**
	 * Set object id in woocommerce meta.
	 *
	 * @param string $feed_id Feed id.
	 * @param string $woo_id  WooCommerce Order/Customer/Product id.
	 * @param string $crm_id  QuickBooks object id.
	 * @param boolen $success Is success.
	 */
	private function set_woo_response_meta( $feed_id, $woo_id, $crm_id, $success ) {
		if ( $success ) {
			update_post_meta( $woo_id, Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '_associated_object_status', true );
			update_post_meta( $woo_id, 'mwb_crm_connect_feed_' . $feed_id . '_association', $crm_id );
		} else {
			// In the case of failed, if any digit is saved keep it safe else delete this meta.
			update_post_meta( $woo_id, Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '_associated_object_status', false );
		}
	}

	/**
	 * Check if resposne has success code.
	 *
	 * @param  array $response  Response data.
	 * @return boolean          Success.
	 */
	public function is_success_response( $response ) {

		if ( ! empty( $response['code'] ) && ( 200 === $response['code'] || 'OK' === $response['message'] ) ) {
			return true;
		} elseif ( ! empty( $response['code'] ) && ( ! empty( $response['data'] ) && 'SUCCESS' === $response['data'] ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Fetch object id of created record.
	 *
	 * @param  array  $response Api response.
	 * @param  string $object  Api object.
	 * @return string           Id of object.
	 */
	public function get_object_id_from_response( $response = array(), $object = '' ) {

		$id = '';

		// If a operation response.
		if ( isset( $response['data'] ) ) {
			$response['data'] = json_decode( $response['data'], ARRAY_A );
			if ( ! isset( $response['data']['QueryResponse'] ) ) {
				$data = ! empty( $response['data'] ) ? $response['data'] : array();
				$id   = reset( $data );
				return ! empty( $id ) && is_numeric( $id ) ? $id : '';
			}
		}

		return $id;
	}

	/**
	 * Insert data into database.
	 *
	 * @param  array $log_data Log data.
	 */
	private function insert_log_data( $log_data ) {

		$connect         = 'Mwb_Cf7_Integration_Connect_' . Mwb_Cf7_Integration_With_Engagebay::get_current_crm() . '_Framework';
		$connect_manager = $connect::get_instance();

		if ( 'yes' != $connect_manager->get_settings_details( 'logs' ) ) { // phpcs:ignore
			return;
		}

		global $wpdb;
		$table = $wpdb->prefix . 'mwb_' . Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' ) . '_cf7_log';
		$wpdb->insert( $table, $log_data ); // phpcs:ignore

	}

	// End of class.
}
