<?php

/**
 * 表单相关业务
 * User:lmh
 * Date:
 * Time:
 */
class Privilege_account_facade extends \Application\Component\Common\IFacade
{

	function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/privilege_account_data' );
		$this->load->model ( 'data/Privilege_account_list_data' );

	}

	/**
	 * 更新激活数量
	 */
	public function upActivated ($KEY)
	{
		$time = time ();
		$token = md5 ( $time .$KEY);
		$webgame_arr = $this->privilege_account_list_data->getPlatformId ( 1 );
		$mobilegame_arr = $this->privilege_account_list_data->getPlatformId ( 2 );
		$webgame_ids = implode ( ',', array_column ( $webgame_arr, 'id' ) );
		$mobilegame_ids = implode ( ',', array_column ( $mobilegame_arr, 'id' ) );
		if ( $webgame_ids ) {
			$server_url = $this->server_url[1];
			$client = new Yar_Client( $server_url );
			try{
				$webgame_arr = $client->getListInfos ( $webgame_ids, $time, $token );
			}catch (Yar_Client_Exception $client_Exception){
				log_message ( '265g_getListInfos_error', $client_Exception->getMessage (), true );
				exit();
			}
			finally{
				foreach ($webgame_arr as $k=>$v){
					if ($v['activated'] == 0){
						continue;
					}
					$this->privilege_account_list_data->update($k,array ('activated'=>$v['activated']));
				}
			}

		}
		if ( $mobilegame_ids ) {
			$server_url = $this->server_url[2];
			$client = new Yar_Client( $server_url );
			try {
				$mobilegame_arr = $client->getListInfos ( $mobilegame_ids, $time, $token );
			} catch ( Yar_Client_Exception $client_Exception ) {
				log_message ( '72g_getListInfos_error', $client_Exception->getMessage (), true );
				exit();
			}finally{
				foreach ($mobilegame_arr as $j=>$y){
//					if ($y['activated'] == 0){
//						continue;
//					}
					$this->privilege_account_list_data->update($j,array ('activated'=>$y['activated']));
				}
			}
		}
	}

	/**
	 * 发布
	 * @param array $response
	 * @return bool
	 */

	public function create ( array $response )
	{
		if ( !$response['platform'] ) {
			$this->set_error ( '平台不能为空' );
			return false;
		}

		if ( !$response['gamename'] ) {
			$this->set_error ( '游戏不能为空' );
			return false;
		}
		if ( !$response['gameid'] ) {
			$this->set_error ( '游戏不能为空' );
			return false;
		}

		if ( !$response['money'] && $response['platform'] !=3 ) {
			$this->set_error ( '单价不能为空' );
			return false;
		}
		if ( !$response['num'] ) {
			$this->set_error ( '数量不能为空' );
			return false;
		}
		$insert_id = $this->Privilege_account_list_data->store ( [
			'platform' => $response['platform'],
			'gamename' => $response['gamename'],
			'gameid' => $response['gameid'],
			'money' => $response['money'],
			'num' => $response['num'],
			'channel_id' => 159,
			'dateline' => time ()
		] );

		if ( !$insert_id ) {
			$this->set_error ( '新增失败' );
			return false;
		}
		return $insert_id;

	}

	public function create_account ( array $response )
	{
		if ( !is_array ( $response ) ) {
			$this->set_error ( '数据不对' );
			return false;
		}
		if ( !$this->privilege_account_data->insert_all ( $response ) ) {
			$this->set_error ( '批量入库账号失败,请稍后再试哦！' );
			return false;
		}
		return true;
	}

	/**
	 * 账号删除
	 * @param $id
	 * @return bool
	 */

	public function delete_account ( $pid, $data )
	{
		if ( !$this->privilege_account_data->delete_account ( $pid, $data ) ) {
			$this->set_error ( '删除失败' );
			return false;
		}
		return true;
	}

	/**
	 * 删除账号记录
	 * @param $id
	 * @return mixed
	 */
	public function delete ( $id )
	{
		return $this->privilege_account_data->detele ( $id );
	}

}