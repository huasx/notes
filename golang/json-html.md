### golang JSON编码时保留HTML标签



* golang默认编码JSON时会将HTML标签中的尖括号编码为\u003c这种unicode字符。而最近在开发的微信客服消息推送就会出现以下结果
``\u003ca href='https://www.example.com'\u003e点击进入\u003c/a\u003e``

* 然而我们希望保留  ``<``  时，需要使用另外一个方法
  json编码器有个方法SetEscapeHTML方法，接收一个bool值来设置是否保留HTML标签。


代码如下

```

func BuildJson(data map[string]interface{}) ([]byte, error) {
    buf := bytes.NewBufferString("")
    encoder := json.NewEncoder(buf)
	encoder.SetEscapeHTML(false)
	if err := encoder.Encode(&data); err != nil {
		return nil, err
	} else {
		return buf.Bytes(), nil
	}
}
```