<?php
/**
 * 抽象数据层
 * User: xiongbaoshan
 * Date: 2015/11/10
 * Time: 13:12
 */
namespace Application\Component\Common;


abstract class IData extends ILayer implements DateInterface
{
    protected $db;//数据库句柄
    protected static  $yaer_data;//年

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('', true);
    }

    /**
     * 获取表名
     * @return string
     */
    public static function _get_table_name()
    {
        $class_name = strtolower(substr(static::class, 0, -5));

        return $class_name;
    }

    /**
     * 获取分表表名
     * @return string
     */
    public static function _sub_table()
    {
        $name = self::$yaer_data ? '_'.self::$yaer_data : '';

        return $name;
    }

    /**
     * 获取主键
     * @return string
     */
    public static function _get_pk_field()
    {
        return 'id';
    }

    /**
     * 判断表是否存在，不存在则创建
     * @return bool
     */
    final public function building_tables()
    {
        if(!$this->db->table_exists(static::_get_table_name().static::_sub_table())){

            $sql = str_replace('{{}}',static::_get_table_name().static::_sub_table(),$this->table_structure->get_values(static::_get_table_name()));

            if (! $res = $this->db->query($sql)) {
                return false;
            }
            //记录该表
            $this->db->insert('table_record', ['table_name'=>static::_get_table_name().static::_sub_table()]);
        }
        return true;
    }

    /**
     * 保存数据(返回产生的ID)
     * @param array $data
     * @param bool $replace
     * @return int
     */
    final public function store($data = array(), $replace = false)
    {
        if(!$this->building_tables()){ return false; }

        if ($replace) {
            $this->db->replace(static::_get_table_name().static::_sub_table(), $data);

        } else {
            $this->db->insert(static::_get_table_name().static::_sub_table(), $data);
        }

        if ($this->db->insert_id() > 0) {
            return $this->db->insert_id();
        }

        if ($this->db->affected_rows() > 0) {
            $r =  $this->db->affected_rows();
            return $r;
        }

        return 0;

    }

    /**
     * 删除数据
     * @param int $pk_id
     * @return bool
     */
    final public function delete($pk_id = 0)
    {
        $this->db->delete(
            static::_get_table_name().static::_sub_table(),
            array(static::_get_pk_field() => $pk_id)
        );
        return $this->db->affected_rows() > 0;
    }

    /**
     * 更新数据(返回更新是否成功)
     * @param int $pk_id
     * @param array $data
     * @return bool
     */
    final public function update($pk_id = 0, $data = array(),$pk_field='')
    {

        if(!$this->building_tables()){ return false; }

        if (!$pk_field){
            $pk_field =  static::_get_pk_field();
        }
        $this->db->update(
            static::_get_table_name().static::_sub_table(),
            $data,
            array(
                $pk_field  => $pk_id
            )
        );

        $re =  $this->db->affected_rows() >= 0;
        return $re;
    }

    /**
     * 列表
     * @param array $condition
     * @return array
     */
    final public function lists($condition = array(), $order_by = array(), $limit = null)
    {
        if (!$order_by) {
            $order_by = array(static::_get_pk_field(), 'asc');
        }
	    $this->db->from(static::_get_table_name().static::_sub_table());
        if (!empty($condition)){
	        if (is_string($condition)) {
		        $this->db->where($condition, null, false);
	        } else {
		        $this->db->where($condition);
	        }
        }
        $this->db->order_by($order_by[0], $order_by[1]);

        if (is_array($limit)) {
            $this->db->limit($limit[0], $limit[1]);
        } elseif (is_numeric($limit)) {
            $this->db->limit($limit);
        }
        $r =  $this->db->get()->result_array();

        return $r;
    }

    /**
     * 统计
     * @param array $condition
     * @return mixed
     */
    final public function count($condition = array())
    {
        if(!$this->db->table_exists(static::_get_table_name().static::_sub_table())){ return false; }//不存在该表时

    	if (empty($condition)) $condition =array ();
        return  $this->db
            ->from(static::_get_table_name().static::_sub_table())
            ->where($condition)
            ->count_all_results();
    }

    /**
     * 分页列表
     * @param array $condition
     * @param int $page_num
     * @param int $page_size
     * @return array
     */
    final public function lists_page($condition = array(), $order_by = array(), $page_num = 1, $page_size = 17)
    {
        if(!$this->db->table_exists(static::_get_table_name().static::_sub_table())){ return false; }//不存在该表时

        $this->load->library('page');
        if (!$order_by) {
            $order_by = array(static::_get_pk_field(), 'asc');
        }
        $total = $this->count($condition);
        $this->page->init($total, $page_num, $page_size);
        $this->db
            ->from(static::_get_table_name().static::_sub_table())
            ->order_by($order_by[0], $order_by[1])
            ->limit(
                $this->page->get_page_size(),
                $this->page->get_page_start()
            );

        if (is_string($condition)) {
            $this->db->where($condition, null, false);
        } else {
            $this->db->where($condition);
        }

        $data = $this->db->get()->result_array();

        return array(
            'page_count' => $this->page->get_page_count(),
            'page_num' => $page_num,
            'page_size' => $page_size,
            'total' => $total,
            'data' => $data
        );
    }

    /**
     * 根据主键,获取数据
     * @param int $pk_id
     * @return array
     */
    final public function get_info($pk_id = 0)
    {
        return $this->_get_info(array(static::_get_pk_field() => $pk_id));
    }

    /**
     * 获取固定字段值
     * @param array $field 需要查询的字段
     * @param array $condition 查询 条件
     * @return array
     */
    final public function get_field_by_where($field =array(), $condition = array(),$returnArr=false)
    {
        $this->db
            ->from(static::_get_table_name().static::_sub_table())
            ->select($field);
        if (!empty($condition)){
	        if (is_string($condition)) {
		        $this->db->where($condition, null, false);
	        } else {
		        $this->db->where($condition);
	        }
        }
        $data = $this->db->get()->result_array();

        if ($returnArr){
        	return $data;
        }
        if (is_string($field)){
            return $data[0][$field];
        }else{
            return $data ? $data[0] : array();
        }
    }

    /**
     * 获取数据
     * @param $condition 查询条件
     * @return array
     */
    final protected function _get_info($condition)
    {
        if (!$condition) {
            return array();
        }
        $result = $this->db->where($condition)->get(static::_get_table_name().static::_sub_table());
        if (!$result) {
            return array();
        }

        $data = (array)$result->result_array();
        return $data ? $data[0] : array();
    }

    /**
     * 根据条件找出一条数据
     * @param $condition
     * @return array
     */
    final public function find($condition){
        return $this->_get_info($condition);
    }

    /**
     * 计数器字段修改（字段递增或者增减）
     * @param $pk_id 主键ID
     * @param $field 要操作的字段
     * @param string $op 操作
     * @param int $step 步进值
     * @param int $lock_value 乐观锁（数据一致性要求非常高时请使用）
     * @return bool
     */

    final public function _counter_modify($pk_id, $field, $op = '+', $step = 1, $lock_value = null)
    {
        $op_enum = ['+', '-'];

        if (!in_array($op, $op_enum)) {
            return false;
        }

        $table = static::_get_table_name().static::_sub_table();
        $pk_field = static::_get_pk_field();

        $step = floatval($step);

        $lock = "";
        if ($lock_value !== null) {
            $lock_value = floatval($lock_value);
            $lock = " and {$field} = {$lock_value} ";
        }

        $this->db->query("update {$table} set {$field}={$field}{$op}{$step} where {$pk_field}={$pk_id} $lock");

        return $this->db->affected_rows() > 0;
    }

}

interface DateInterface
{

    /**
     * 返回表名
     * @return string
     */
    public static function _get_table_name();

    /**
     * 返回分表表名
     * @return mixed
     */
    public static function _sub_table();

    /**
     * 返回主键
     * @return string
     */
    public static function _get_pk_field();

}