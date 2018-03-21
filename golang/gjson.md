### 针对一些用户输入的数据，转换为的json格式，或者无法保证json格式的规范，可以使用gjson来解析



* 例如 保存的json数据是
``{"min_fee":"111","max_fee":"22222"}``

* 使用gjson解析后
`json_str := gjson.Parse(json)`

* 打印``json_str``
`{"min_fee":"111","max_fee":"22222"}`

* 获取某一个值就可以这么写
`json_str.Get("min_fee")`


* 或者
`gjson.Get(json, "min_fee"))`