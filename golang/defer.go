/*
package main

import (
	"time"
	"fmt"
	"strconv"
)

func main() {
	timestamp := time.Now().Unix() - 86400
	fmt.Println(timestamp)
	t, _ := time.Parse("2006-01-02 15:04:05", strconv.FormatInt(timestamp,10))
	fmt.Println(strconv.FormatInt(timestamp,10))
	fmt.Println(t)
}
*/

package main

import (
    "io"
    "net/http"
	"fmt"
)

func hello(rw http.ResponseWriter, req *http.Request) {
	fmt.Println("333")
    io.WriteString(rw, "hello widuu")
	fmt.Println("444")
}

/*func main() {
	fmt.Println("111111")
    http.HandleFunc("/", hello)  //设定访问的路径
	s := strings.Split("abc,abc", ",")
   fmt.Println(s[0], len(s))
    http.ListenAndServe(":8080", nil) //设定端口和handler
}*/

func begin(funName string) string {
    fmt.Println("Enter Function")
    return     funName
}
func end(funName string) string {
    fmt.Println("Leave Function")
    return     funName
}
func record() {
    fmt.Println("record Function")
}
func main(){
    defer end(begin("func"))
    record()
}


//输出
Enter Function
record Function
Leave Function
