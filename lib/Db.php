<?php

/*
 * 数据库访问类
 */

class Db
{
    private $conn = null;
    private $table = null;
    private $where = [];
    private $field = '*';
    private $order = null;
    private $limit = 0;

    public function __construct()
    {
        $this->conn = new PDO('mysql:host=localhost;dbname=phpblog', 'root', 'root');
        //设置为异常模式
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    //指定查询数量
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    //指定表名
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    // 指定where条件
    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    //指定查询字段
    public function field($field)
    {
        $this->field = $field;
        return $this;
    }

    //指定排序
    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    //返回一条数据记录
    public function item()
    {
        try {
            $sql = $this->buiild_sql('select') . ' limit 1';
            $smtp = $this->conn->prepare($sql);
            $smtp->execute();
            $res = $smtp->fetchAll(PDO::FETCH_ASSOC);
            return $res = isset($res[0]) ? $res[0] : false;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //返回多条记录
    public function list()
    {
        try {
            $sql = $this->buiild_sql('select');;
            $smtp = $this->conn->prepare($sql);
            $smtp->execute();
            return $smtp->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //添加数据
    public function insert($data)
    {
        $sql = $this->buiild_sql('insert', $data);
        $smtp = $this->conn->prepare($sql);
        $smtp->execute();
        return $this->conn->lastInsertId();
    }

    //删除语句并返回影响的行数
    public function delete()
    {
        $sql = $this->buiild_sql('delete');
        $smtp = $this->conn->prepare($sql);
        $smtp->execute();
        return $smtp->rowCount();
    }

    //更新数据
    public function update($data)
    {
        $sql = $this->buiild_sql('update', $data);
        $smtp = $this->conn->prepare($sql);
        $smtp->execute();
        return $smtp->rowCount();
    }

    //查询数据总数
    public function count()
    {
        $sql = $this->buiild_sql('count');
        $smtp = $this->conn->prepare($sql);
        $smtp->execute();
        $total = $smtp->fetchColumn(0);
        return $total;
    }
    //分页
    //limit 0,3
    public function pages($page, $pageSize = 3, $path = '/')
    {
        $count = $this->count();
        $this->limit = ($page - 1) * $pageSize . ',' . $pageSize;
        $data = $this->list();
        $pages = $this->subPages($page, $pageSize, $count, $path);
        return array('count' => $count, 'data' => $data, 'pages' => $pages);
    }

    //生成分页html(bootstrap) cur_page:当前第几页 total:数据总数 pageSize:每页数据数 page_count:分页数 path:环境
    private function subPages($cur_page, $pageSize, $total, $path)
    {
        $symbol = '?';
        //判断参数path是有问号
        $index = strpos($path, '?');
        if ($index > 0 && $index !== false) {
            $symbol = '&';
        }
        //分页数向上取整
        $html = '';
        $page_count = ceil($total / $pageSize);
        //生成上一页 生成首页
        if ($cur_page > 1) {
            $html .= "<li class='page-item'><a class='page-link' href='{$path}{$symbol}page=1'>首页</a></li>";
            $pre_page = $cur_page - 1;
            $html .= "<li class='page-item'><a class='page-link' href='{$path}{$symbol}page={$pre_page}'>上一页</a></li>";
        }
        //生成数字页
        $start = $cur_page > ($page_count - 6) ? ($page_count - 6) : $cur_page;
        $start = $start - 2;
        $start = $start <= 0 ? 1 : $start;
        $end = ($cur_page + 6) > $page_count ? $page_count : ($cur_page + 6);
        $end = $end - 2;
        if ($cur_page + 2 >= $end && $page_count >= 6) {
            $start = $start + 2;
            $end = $end + 2;
        }
        for ($i = $start; $i <= $end; $i++) {
            $html .= $i == $cur_page ? "<li class='page-item active'><a class='page-link'>{$i}</a></li>" : "<li class='page-item'><a class='page-link' href='{$path}{$symbol}page={$i}'>{$i}</a></li>";
        }
        //生成下一页 生成尾页
        if ($cur_page < $page_count) {
            $next_page = $cur_page + 1;
            $html .= "<li class='page-item'><a class='page-link' href='{$path}{$symbol}page={$next_page}'>下一页</a></li>";
            $html .= "<li class='page-item'><a class='page-link' href='{$path}{$symbol}page={$page_count}'>尾页</a></li>";
        }

        $html = '<nav aria-label="Page navigation">
        <ul class="pagination">' . $html . '</ul></nav>';

        return $html;
    }

    //构造sql语句
    private function buiild_sql($type, $data = null)
    {
        //查询
        if ($type == 'select') {
            $where = $this->build_where();
            $sql = "select {$this->field} from {$this->table} {$where}";
            if ($this->order) {
                $sql .= " order by {$this->order}";
            }
            if ($this->limit) {
                $sql .= " limit {$this->limit}";
            }
        }
        //count
        if ($type == 'count') {
            $where = $this->build_where();
            //解决sql count多个字段会报错
            $filed_list = explode(',', $this->field);
            $field = count(filter_list()) > 1 ? '*' : $this->field;

            $sql = "select count({$field}) from {$this->table} {$where}";
        }
        //添加
        if ($type == 'insert') {
            $sql = "insert into {$this->table}";
            $field = $values = [];
            //调用自带函数
            //$field=array_keys($data);
            //$values=array_values($data);
            //自写方法
            foreach ($data as $key => $value) {
                $field[] = $key;
                $values[] = is_string($value) ? "'" . $value . "'" : $value;
            }
            $sql .= "(" . implode(',', $field) . ") values(" . implode(',', $values) . ")";
        }
        //删除
        if ($type == 'delete') {
            $where = $this->build_where();
            $sql = "delete from {$this->table} {$where}";
        }
        //更新
        if ($type == 'update') {
            $where = $this->build_where();
            //生成 set
            $str = '';
            foreach ($data as $key => $value) {
                $value = is_string($value) ? "'" . $value . "'" : $value;
                $str .= "{$key}={$value},";
            }
            $str = rtrim($str, ',');
            $str = $str ? " set {$str}" : '';
            $sql = "update {$this->table} {$str} {$where}";
        }
        return $sql;
    }

    //组装where条件字符串
    private function build_where()
    {
        $where = '';
        if (is_array($this->where)) {
            //数组条件拼接
            foreach ($this->where as $key => $value) {
                $value = is_string($value) ? "'" . $value . "'" : $value;
                $where .= "{$key}={$value} and ";
            }
            $where = rtrim($where, 'and ');
            //字符串
        } else {
            $where = $this->where;
        }
        $where = $where == '' ? '' : "where {$where}";
        return $where;
    }
}