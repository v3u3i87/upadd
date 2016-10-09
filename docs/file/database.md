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

## 去重统计(count_distinct)

```
$this->_fund->where(['status' => 1])->count_distinct('id', $field);
```

说明:
    $field:需要返回的参数

## 去重返回列表(get_distinct)

```
$this->_fund->where(['status' => 1])->get_distinct('id', $field);
```

说明:
    $field:需要返回的参数

## 排序(sort)
## 构造分页参数(page)

```
self::where($so)->page($page)->sort('id')->get($field);
```

说明:
    * 此例以ID为主键排序
    * $page表示分页个数

## 模糊查询(like)

```
$key = 'b.phone';
$value = '187****0826';
self::where($select)->join(['project' => 'a', 'financing' => 'b'])->like($key, $value)->page($page)->sort('b.project_id')->get($field)
```

说明:
    * $select查询条件
    * $key 查询key
    * $value 查询值

## 查询条数(limit)

```
$info = $this->where(['is_use' => 0])->limit('1')->find(['id','code']);
```

## 新增(add)

```
$member['type'] = 0;
$member['id'] = 12;
$member['from'] = 1;
$member['status'] = 1;
$member['update_time'] = time();
$member['create_time'] = time();
$up = $this->_member->add($member);
```
说明:
    * $up返回0表示添加失败
    * 添加成功$up返回当前记录id

## 保存数据(save)

```
SmsModel::where(['id' => $sms_id])->save([
                'is_send' => $is_send,
                'status' => $result,
                'return_time' => time(),
                'update_time' => time()
            ]);
```

## 批量添加(addAll)

```
foreach ($array as $key => $value) {
  $data[$key]['code'] = $value;
  $data[$key]['update_time'] = time();
  $data[$key]['create_time'] = time();
}
$result = $invite_code_model->addAll($data);
```

说明:
   * $result为所有的添加数据id数组

## 修改数据(update)

```
$up = $this->member->update(['code' => $code], ['id' => $id]);
```

说明:
    * $up返回0表示更新失败
    * 更新成功$up返回大于0


## 删除(del)

```
$up = $this->member->del(['id' => $id]);
```

说明:
    * $up返回0表示删除失败
    * 删除成功$up返回大于0


## 运行sql(sql)

```
$this->_db->_sql = "UPDATE `{$this->_table}` SET {$_editdata}  WHERE {$_where};";
$this->_db->sql();
```

## 返回当前新增ID(getId)

```
$this->_db->_sql = "INSERT INTO `{$this->_table}` (`$field`) VALUES ('$value') ;";
if ($this->_db->sql()) {
    return $this->getId();
}
```

## 获取表字段(getField)

```
$fields = $this->_member_info->getField();
```

## 获取下条自增ID(getNextId)

```
$this->_db->_sql = "SHOW TABLE STATUS LIKE `{$this->_table}`;";
return $this->_db->getNextId();
```

## 锁表 Mysql in MyISAM(lock)

```
if ($type) {
    $this->_db->_sql = "LOCK TABLES `{$this->_table}` WRITE;";
} else {
    $this->_db->_sql = "LOCK TABLES `{$this->_table}` READ;";
}
return $this->_db->sql();
```

## 解锁 Mysql in MyISAM(unlock)

```
$this->_db->_sql = " UNLOCK TABLES;";
return $this->_db->sql();
```

## 获取当前查询条件表总数(getTotal)

```
$this->joint_field('COUNT(*) AS `conut` ');
$this->_db->_sql = 'SELECT ' . $this->mergeSqlLogic() . ';';
return $this->_db->getTotal();
```

## getTotal别名(count)

```

```

## 打印当前运行的SQL(p)

## 开启事务(begin)
## 提交事务并结束(commit)
## 回滚事务(rollback)

```
$model = $this->_model;
$model->begin();
$code = $data['code'];
$member['is_vip'] = 1;
if ($code) {
    $member['code'] = '123123';
}
$up = $model->update($member, ['id' => $id]);
if (!$up) {
    $model->rollback();
    return $this->error('failed', 206);
}
//更新资料
$info_model = $this->_member_info;
$member_info['truename'] = $data['truename'];
$member_info['reason'] = $data['reason'];
$member_info['businesscardimg'] = $data['businesscardimg'];
$up = $info_model->update($member_info, ['member_id' => $id]);
if ($up) {
    $model->commit();
    return $this->success(['flag' => 1], null, 'ok');
} else {
    $model->rollback();
    return $this->error('failed', 206);
}
```

说明:
    * begin开启事物
    * 如果操作失败,则回滚rollback
    * 只有所有的操作都成功之后,提交并结束commit
    * 事务对应一个model即可


## 返回错误信息(error)

## 返回上一条影响行数(rowCount)



## 返回数据库的所有表名(showTables)


## 通过主键查询(first)




