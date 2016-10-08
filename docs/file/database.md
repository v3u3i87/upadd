# 数据库相关函数

## 查询列表(get)

**使用方法:**

```
self::where($so)->page($page)->sort('id')->get($field);
```
说明:
   * $so查询条件
   * $page为每次查找的条数
   * sort通过什么来排序
   * field为你需要获取的参数

## 单行查询(find)

```
$fundsShow = $this->_fund->where(['id' => $funds_id, 'status' => 1])->find($field);
```

## 多表查询(join)

```
$so = 'a.id = b.project_id AND b.status IN (1,2,3,4,5)';
self::where($so)->join(['table1' => 'a', 'table2' => 'b'])->page($page)->sort('b.id')->get($field);
```

## where判断(where)

```
$so['id'] =  $funds_id;
$so['status'] =  1;

$fundsShow = $this->_fund->where($so)->find($field);
```
说明:
    * $so查询条件

## OR 类型查询(or_where)

```
$num = $funds->or_where(['member_id' => $member_id], ['manage_id' => $member_id])->count();
```

## InWhere类型查询(in_where)

```
$fund_arr = [1,2,3,4,5];
$detail = $this->_fund->where(['status' => 1])->in_where('id', $fund_arr)->page($page)->sort('id')->get();
```

说明:
    * $fund_arr为数组

## notWhere查询(not_where)

```
 $detail = $this->_member_flows->where(['mode' => 8, 'member_id' => $member_id])->not_where('project_id', $ids)->page()->get();
```

说明:
    * $ids为数组

## 返回数据库的所有表名(showTables)


## 通过主键查询(first)




