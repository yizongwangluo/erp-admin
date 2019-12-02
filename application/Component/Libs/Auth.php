<?php
// +----------------------------------------------------------------------
// | For CodeIgniter3 By Storm Tan
// * @author：storm
//* Email：hi@yumufeng.com
// +----------------------------------------------------------------------
namespace Application\Component\Libs;

/**
 * 权限认证类
 * 功能特性：
 * 1，是对规则进行认证，不是对节点进行认证。用户可以把节点当作规则名称实现对节点进行认证。
 *      $auth=new Auth();  $auth->check('规则名称','用户id')
 * 2，可以同时对多条规则进行认证，并设置多条规则的关系（or或者and）
 *      第三个参数为and时表示，用户需要同时具有规则1和规则2的权限。 当第三个参数为or时，表示用户值需要具备其中一个条件即可。默认为or
 * 3，一个用户可以属于多个用户组(auth_group_access表 定义了用户所属用户组)。我们需要设置每个用户组拥有哪些规则(auth_group 定义了用户组权限)
 * 4，支持规则表达式。
 *      在auth_rule 表中定义一条规则时，如果type为1， condition字段就可以定义规则表达式。 如定义{score}>5  and {score}<100
 * 表示用户的分数在5-100之间时这条规则才会通过。
 */
class Auth
{
	/**
	 * @var object 对象实例
	 */
	protected static $instance = array ();
	/**
	 * 当前请求实例
	 * @var Request
	 */
	protected $ci;
	/**
	 * 当前数据库链接
	 * @author：storm
	 */
	protected $db;

	//默认配置
	protected $config = [
		'auth_on' => 1, // 权限开关
		'auth_type' => 1, // 认证方式，1为实时认证；2为登录认证。
		'auth_group' => 'admin_auth_group', // 用户组数据表名
		'auth_group_access' => 'admin_auth_group_access', // 用户-用户组关系表，一个用户一个组的话，可以不启用！
		'auth_rule' => 'menu', // 节点权限规则表
		'auth_user' => 'admin', // 用户信息表
	];

	/**
	 * 类架构函数
	 * Auth constructor.
	 */
	public function __construct ()
	{
		// 初始化request
		if ( !isset( self::$instance['ci'] ) ) {
			$this->ci = self::$instance['ci'] = &\get_instance ();
		} else {
			$this->ci = self::$instance['ci'];
		}
		if ( !isset( self::$instance['db'] ) ) {
			$this->db = self::$instance['db'] = $this->ci->load->database ( '', true );
		} else {
			$this->db = self::$instance['db'];
		}
	}

	/**
	 * 检查权限
	 * @param        $name     string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
	 * @param        $uid      int           认证用户的id
	 * @param int $type 认证类型
	 * @param string $mode 执行check的模式
	 * @param string $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
	 * @return bool               通过验证返回true;失败返回false
	 */
	public function check ( $name, $uid, $type = 1, $mode = 'url', $relation = 'or' )
	{
		if ( !$this->config['auth_on'] ) {
			return true;
		}
		// 获取用户需要验证的所有有效规则列表
		$authList = $this->getAuthList ( $uid, $type ,$mode);
		if ( is_string ( $name ) ) {
			$name = strtolower ( $name );
			if ( strpos ( $name, ',' ) !== false ) {
				$name = explode ( ',', $name );
			} else {
				$name = [$name];
			}
		}
		$list = []; //保存验证通过的规则名
		if ( 'url' == $mode ) {
			$REQUEST = unserialize ( strtolower ( serialize ( $_REQUEST ) ) );
		}
		foreach ( $authList as $auth ) {
			$query = preg_replace ( '/^.+\?/U', '', $auth );
			if ( 'url' == $mode && $query != $auth ) {
				parse_str ( $query, $param ); //解析规则中的param
				$intersect = array_intersect_assoc ( $REQUEST, $param );
				$auth = preg_replace ( '/\?.*$/U', '', $auth );
				if ( in_array ( $auth, $name ) && $intersect == $param ) {
					//如果节点相符且url参数满足
					$list[] = $auth;
				}
			} else {
					if ( in_array ( $auth, $name ) ) {
						$list[] = $auth;
					}
			}
		}
		if ( 'or' == $relation && !empty( $list ) ) {
			return true;
		}
		$diff=[];
		if (!empty($name)){
			$diff = array_diff ( $name, $list );
		}
		if ( 'and' == $relation && empty( $diff ) ) {
			return true;
		}

		return false;
	}

	/**
	 * 根据用户id获取用户组,返回值为数组
	 * @param  $uid int     用户id
	 * @return array       用户所属的用户组 array(
	 *              array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
	 *              ...)
	 */
	public function getGroups ( $uid )
	{
		static $groups = [];
		if ( isset( $groups[$uid] ) ) {
			return $groups[$uid];
		}
		// 转换表名
		$auth_group = $this->config['auth_group'];
		$admin = $this->config['auth_user'];

		//查询
		$user_role_ids = $this->db->query ( "select * from {$admin} where id ={$uid}" )->result_array ()[0];
		$sql = "select * from {$auth_group} where `status`=1 and id in ({$user_role_ids['role_id']})";
		$user_groups = $this->db->query ( $sql )->result_array ();
		$arr = [];
		if($user_groups){
			foreach($user_groups as $k=>$v){
				$arr[$k]['uid'] = $uid;
				$arr[$k]['group_id'] = $v['id'];
				$arr[$k]['title'] = $v['title'];
				$arr[$k]['rules'] = $v['rules'];
			}
		}
		$groups[$uid] = $arr ?: [];
		return $groups[$uid];
	}

	/**
	 * 获得权限列表
	 * @param integer $uid 用户id
	 * @param integer $type
	 * @return array
	 */
	protected function getAuthList ( $uid, $type ,$mode='url')
	{
		static $_authList = []; //保存用户验证通过的权限列表
		$t = implode ( '_', (array)$type );
		if ( isset( $_authList[$uid . $t.$mode] ) ) {
			return $_authList[$uid . $t.$mode];
		}
		if ( 2 == $this->config['auth_type'] && isset( $_SESSION['_auth_list_' . $uid . $t.$mode] ) ) {
			return $_SESSION['_auth_list_' . $uid . $t.$mode];
		}
		//读取用户所属用户组
		$groups = $this->getGroups ( $uid );
		$ids = []; //保存用户所属用户组设置的所有权限规则id
		foreach ( $groups as $g ) {
			$ids = array_merge ( $ids, explode ( ',', trim ( $g['rules'], ',' ) ) );
		}
		$ids = array_unique ( $ids );
		if ( empty( $ids ) ) {
			$_authList[$uid . $t.$mode] = [];

			return [];
		}
		$ids = implode ( ',', $ids );
		//读取用户组所有权限规则
		$rulessql = "SELECT  id,`condition`,url AS name FROM {$this->config['auth_rule']} WHERE type ='{$type}' AND id IN ({$ids})";
		$rules = $user_groups = $this->db->query ( $rulessql )->result_array ();

		//循环规则，判断结果。
		$authList = []; //
		foreach ( $rules as $rule ) {
			if ( !empty( $rule['condition'] ) ) {
				//根据condition进行验证
				$user = $this->getUserInfo ( $uid ); //获取用户信息,一维数组
				$command = preg_replace ( '/\{(\w*?)\}/', '$user[\'\\1\']', $rule['condition'] );
				@(eval( '$condition=(' . $command . ');' ));
				if ( $condition ) {
					if ($mode == 'url'){
						$authList[] = strtolower ( $rule['name'] );
					}else{
						$authList[] = strtolower ( $rule['id'] );
					}
				}
			} else {
				//只要存在就记录
				if ($mode == 'url'){
					$authList[] = strtolower ( $rule['name'] );
				}else{
					$authList[] = strtolower ( $rule['id'] );
				}
			}
		}
		$_authList[$uid . $t.$mode] = $authList;
		if ( 2 == $this->config['auth_type'] ) {
			//规则列表结果保存到session
			$_SESSION['_auth_list_' . $uid . $t.$mode] = $authList;
		}
		return array_unique ( $authList );
	}

	/**
	 * 获得用户资料
	 * @param $uid
	 * @return mixed
	 */
	protected function getUserInfo ( $uid )
	{
		static $user_info = [];
		if ( !isset( $user_info[$uid] ) ) {
			$userInfoSql = "select * from {$this->config['auth_user']} WHERE id='$uid'";
			$user_info[$uid] = $this->db->query ( $userInfoSql )->row_array ();
		}
		return $user_info[$uid];
	}
}