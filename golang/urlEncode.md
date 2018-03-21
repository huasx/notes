import (
    fm "fmt"
    "net/url"
    rt "runtime"
)

func main() {
    var urlStr string = "http://baidu.com/index.php/?abc=1_羽毛"
    l, err := url.ParseQuery(urlStr)
    fm.Println(l, err)
    l2, err2 := url.ParseRequestURI(urlStr)
    fm.Println(l2, err2)

    l3, err3 := url.Parse(urlStr)
    fm.Println(l3, err3)
    fm.Println(l3.Path)
    fm.Println(l3.RawQuery)
    fm.Println(l3.Query())
    fm.Println(l3.Query().Encode())

    fm.Println(l3.RequestURI())
    fm.Printf("Hello World! version : %s", rt.Version())
}



//使用 net/url包
//实例化Values结构 是一个map[string][]string结构，所以实例化可以这样
c := url.Values{"method": {"show", "aaaa"}, "id": {"1"}}
fmt.Println(c.Encode())
