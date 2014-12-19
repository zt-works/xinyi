<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ty
 * Date: 14-6-25
 * Time: 下午2:33
 * To change this template use File | Settings | File Templates.
 */

include("class_qi_niu.php");
class Resource_Controller {
    private $own_table="xy_resources";
    private $per_load=10;


    /**
     * 获取视频管理列表
     * @param $offset
     * @return mixed
     */
    public function get_common_resources($offset,$filename){
        global $wpdb;

        if($filename){
            $filter="AND name LIKE '%$filename%'";
        }
        $query="SELECT id, name,resource_date,url,type,status FROM $this->own_table WHERE status!=-1
        AND user_name='' $filter ORDER BY id DESC LIMIT $this->per_load OFFSET $offset";
        return $wpdb->get_results($query,OBJECT);
    }

    public function get_personal_resources($offset,$user_name){
        global $wpdb;
        $user_filter=$user_name?"user_name=$user_name":"user_name!=''";
        $query="SELECT id, name,resource_date,url,user_name,type,status FROM $this->own_table WHERE status!=-1
        AND $user_filter ORDER BY id DESC LIMIT $this->per_load OFFSET $offset";
        return $wpdb->get_results($query,OBJECT);
    }

    /**
     * 获取视频总数
     * @return mixed
     */
    public function get_common_resources_count($filename){
        global $wpdb;
        $filter="";
        if($filename){
            $filter="AND name LIKE '%$filename%'";
        }
        $query="SELECT COUNT(*) FROM $this->own_table WHERE status!=-1 AND user_name='' $filter";
        return $wpdb->get_var($query);
    }

    public function get_personal_resources_count($user_name){
        global $wpdb;
        $user_filter=$user_name?"user_name=$user_name":"user_name!=''";
        $query="SELECT COUNT(*) FROM $this->own_table WHERE status!=-1 AND $user_filter";
        return $wpdb->get_var($query);
    }

    public function delete_resource($resource_id){
        global $wpdb;
        return $wpdb->update($this->own_table,array("status"=>-1),array("id"=>$resource_id));
    }



    /**
     * 添加视频
     */
    public function add_resource(){
        global $wpdb;

        //return false when error
        $result=$wpdb->insert($this->own_table,array(
            "name"=>$_POST["name"],
            "key_name"=>$_POST["key_name"],
            "resource_date"=>$_POST["resource_date"],
            "user_name"=>$_POST["user_name"],
            "url"=>$_POST["url"],
            "type"=>$_POST["type"],
            "status"=>$_POST["status"]
        ),array("%s","%s","%s","%s","%s","%d","%d"));

        if(!$result){
            die(json_encode(array("success"=>false)));
        }else{
            die(json_encode(array("success"=>true)));
        }

    }

    /**
     *添加自定义表格
     */
    public function add_table(){
        global $wpdb,$jal_db_version;

        $jal_db_version="1.0";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        //判断是否存在表格，如果不存在创建表格
        if($wpdb->get_var("SHOW TABLES LIKE '$this->own_table'")!=$this->own_table){
            $sql="CREATE TABLE ".$this->own_table." (
              id bigint(20) NOT NULL AUTO_INCREMENT,
              name varchar(64) NOT NULL,
              resource_date varchar(32),
              user_name char(64),
              key_name char(128) NOT NULL,
              url varchar(256) NOT NULL,
              type smallint(2) NOT NULL,
              status smallint(2) NOT NULL,
              PRIMARY KEY (id)
            ) DEFAULT CHARSET=utf8;";
            //type 1:video 2:flash
            //status 状态码，0（成功），1（等待处理），2（正在处理流媒体），3（处理流媒体失败），4（通知提交失败）。
            dbDelta( $sql );

            add_option( "jal_db_version", $jal_db_version );
        }
    }

    public function create_upload_token(){
        $qi_niu=new Qi_Niu();
        die(json_encode($qi_niu->create_upload_token()));
    }

    /**
     *触发切片后，七牛的回调地址处理函数
     */
    public function receive_m3u8_url(){
        global $wpdb;

        //error_log($GLOBALS['HTTP_RAW_POST_DATA'],3,get_template_directory()."/pages/log.log");

        $qi_niu=new Qi_Niu();
        $receive_data=json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
        $key=$receive_data["items"][0]["key"];
        $persistent_id=$receive_data["id"];
        $code=$receive_data["code"];

        $m3u8_url="http://".$qi_niu->bucket.".".$qi_niu->qi_niu_dn."/".$key;

        if($code==0){
            //成功
            $wpdb->update($this->own_table,array("m3u8_url"=>$m3u8_url,
                "status"=>$code),array("m3u8_url"=>$persistent_id));
        }else{
            $wpdb->update($this->own_table,array("status"=>$code),array("m3u8_url"=>$persistent_id));
        }
    }
}