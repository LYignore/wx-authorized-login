namespace php src.Thrift.Client

// 返回结构体
struct Response {
    1: i32 code;    // 返回的状态码
    2: string msg;  // 返回的提示语
    3: string data; // 返回的唯一票据ticket
}

// 服务体
service LoginCommonCallService {
    // json字符串参数 客户端请求方法
    Response notify(1: string params)
}
