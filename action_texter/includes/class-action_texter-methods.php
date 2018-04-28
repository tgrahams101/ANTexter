<?php
    /** *
    *Plugin Name: Action Texts
    *Description: Send custom text messages to groups of users on Action Network
    *Author: CodeWalker Institute
    *Version: 1.0
    **/

class ANTexterMethods {
  private $SMS_CAUCUS_URL = 'http://sms-caucus.herokuapp.com/';
  private $AN_URL = 'https://actionnetwork.org/api/v2/';
  private $FORMS_URL = 'https://actionnetwork.org/api/v2/forms';

  public function send_test_text() {
      $postData = array(
          "to" => strval($_POST['to']),
          "body" => strval($_POST['body']),
          "tasid" => get_field( 'twilio_account_sid', 'user_'. get_current_user_id()),
          "tat" => get_field( 'twilio_auth_token', 'user_'. get_current_user_id()),
          "from" => get_field( 'twilio_from_number', 'user_'. get_current_user_id()),
          "apiKey" => get_field('action_texts_api_key', 'user_'. get_current_user_id())
      );
      $response = wp_remote_post($SMS_CAUCUS_URL . 'send-test-text', array( "body" => $postData));

      if ( is_wp_error( $response ) ) {
          $error_message = $response->get_error_message();
          echo "Something went wrong: $error_message";
      }
      echo $response['body'];

      wp_die(); // this is required to terminate immediately and return a proper response
  }

  public function fetch_forms() {
    $api_key = get_field( 'action_network_api_key', 'user_'. get_current_user_id());
    $response = wp_remote_get($FORMS_URL, array(
                'headers' => array('OSDI-API-Token' => $api_key)
                ));

    if (is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      echo "Something went wrong: $error_message";
    }
    echo $response["body"];

    wp_die();
  }

  public function get_flows() {
    $api_key = get_field('action_texts_api_key', 'user_'. get_current_user_id());
    $response = wp_remote_get($SMS_CAUCUS_URL . 'sms-flow/' . $api_key);

    if (is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      echo "Something went wrong: $error_message";
    }
    echo $response["body"];

    wp_die();
  }

  public function post_flow() {
    $api_key = get_field('action_texts_api_key', 'user_'. get_current_user_id());
    $post_body = stripslashes($_POST['body']);
    $postData = array(
      'title'=> 'Example flow',
      'activationKeyword' => 'GUCCI',
      'foreignPath'=> 'POST',
      'steps' => array(
        array(
          'prompt' => 'What is your email?',
          'foreignName' => 'email'
        ),
        array(
          'prompt'=> 'What is your name?',
          'foreignName' => 'name'
        ),
        array(
          'prompt'=> 'What is your zip code?',
          'foreignName' => 'zipcode'
        )
      )
    );

    $response = wp_remote_post($SMS_CAUCUS_URL . 'sms-flow/' . $api_key, array(
      'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
      'body'      => $post_body,
      'method'    => 'POST'
    ));

    if (is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      echo "Something went wrong: $error_message";
    }
    echo $response["body"];

    wp_die();
  }

  public function put_flow() {
    $api_key = get_field('action_texts_api_key', 'user_'. get_current_user_id());
    $put_body = stripslashes($_POST['body']);

    $response = wp_remote_post($SMS_CAUCUS_URL . 'sms-flow/' . $api_key, array(
      'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
      'body'      => $put_body,
      'method'    => 'PUT'
  ));

    if (is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      echo "Something went wrong: $error_message";
    }
    echo $response["body"];

    wp_die();
  }

  public function send_bulk_text() {
    $postData = array(
        "body" => strval($_POST['body']),
        "antid" => strval($_POST["tags"]),
        "anak" => get_field( 'action_network_api_key', 'user_'. get_current_user_id()),
        "tasid" => get_field( 'twilio_account_sid', 'user_'. get_current_user_id()),
        "tat" => get_field( 'twilio_auth_token', 'user_'. get_current_user_id()),
        "from" => get_field( 'twilio_from_number', 'user_'. get_current_user_id()),
        "apiKey" => get_field('action_texts_api_key', 'user_'. get_current_user_id())
    );

    $response = wp_remote_post($SMS_CAUCUS_URL . 'bulk-send', array( "body" => $postData));

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
    }
    echo $response['body'];

    wp_die(); // this is required to terminate immediately and return a proper response
  }

  public function fetch_batches() {
    $api_key = get_field( 'action_texts_api_key', 'user_'. get_current_user_id() );
    $response =   $response = wp_remote_get($SMS_CAUCUS_URL . 'check-all-stats?apiKey=' . $api_key );

    if (is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      echo "Something went wrong: $error_message";
    }
    echo $response['body'];

    wp_die();
  }

  public function fetch_tags() {
    $api_key = get_field( 'action_network_api_key', 'user_'. get_current_user_id());
    $response = wp_remote_get($AN_URL . 'tags/', array(
              'headers' => array('OSDI-API-Token' => $api_key)
                 )
                );

    if (is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      echo "Something went wrong: $error_message";
    }
    echo $response['body'];

    wp_die();
  }

  public function check_progress() {
    $response = wp_remote_get($SMS_CAUCUS_URL . 'check-stats?pid=' . $_GET['pid'] . '&apiKey=' . get_field( 'action_texts_api_key', 'user_'. get_current_user_id()) );

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
    }
    echo $response['body'];

    wp_die(); // this is required to terminate immediately and return a proper response
  }

}



?>
