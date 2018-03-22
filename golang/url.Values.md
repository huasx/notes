### PHP的数组结构很强大，golang里面如何组装PHP的多维数组

php 代码

```
$a = [
    'a' => "1111",
    'b' => "2222",
    'c' => "3333",
    'd' => [
        "e" => "4444",
        "f" => "555"
    ]
];
var_dump(http_build_query($a));
```
输出：
``1111&b=2222&c=3333&d%5Be%5D=4444&d%5Bf%5D=555``

用 JavaScript unescape() 解密

``1111&b=2222&c=3333&d[e]=4444&d[f]=555``


Golang 代码

```
	aa := url.Values{}
	aa.Add("a", "1111")
	aa.Add("b", "2222")
	aa.Add("c", "3333")
	aa.Add("d[e]", "4444")
	aa.Add("d[f]", "555")
	str := aa.Encode()
	fmt.Println(str)
```

输出：
``1111&b=2222&c=3333&d%5Be%5D=4444&d%5Bf%5D=555``

用 JavaScript unescape() 解密

``1111&b=2222&c=3333&d[e]=4444&d[f]=555``



完美，两次一样