/**
 * 任意组合排序
 **/
var str = [1,2,3,4,5];
var count = 0;
function arrange(s){
    for(var i=0,length=str.length; i<length; i++) {
        if(s.length == length - 1) {
            if(s.indexOf(str[i]) < 0) {
                count++;
                console.log("组合"+count+"="+s + str[i]);
            }
            continue;
        }
        if(s.indexOf(str[i]) < 0) {
            arrange(s+str[i]);
        }
    }
}
arrange("");

//第二种

var data = ['1','2','3','4','5'];

function getGroup(data, index = 0, group = []) {
    var need_apply = new Array();
    need_apply.push(data[index]);
    for(var i = 0; i < group.length; i++) {
        need_apply.push(group[i] + data[index]);
    }
    group.push.apply(group, need_apply);

    if(index + 1 >= data.length) return group;
    else return getGroup(data, index + 1, group);
}

var a = getGroup(data);
console.log(a);