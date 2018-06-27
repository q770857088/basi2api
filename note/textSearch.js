/**
 * @title:实现多关键词搜索
 * @author:王迪<wangxiaoxingfu@gmail.com>
 * @description:实现字符串中的多个关键词的模糊查找,
 * 比如,数组中有成都和泼水节,搜索'成泼水' 结果会是:'成都,泼水节';
 */

let tags = [
    '成都',//目的地 0
    '北京',//1
    '杭州',//2
    '鱼香肉丝',//3食
    '锅包肉',//4
    '宫保鸡丁',//5
    '辣椒炒肉',//6
    '鱼香茄子',//7
    '蓝天别墅',//8////住
    '海景别墅',//9
    '山景别墅',//10
    '河流别墅',//11
    '树屋',//12
    '泼水节',//13////节
    '端午节',//14
    '桃花节',//15
    '樱花季',//16
    '玫瑰季',//17
    '西湖美景',//18///地
    '人民英雄纪念碑',//19
    '玉林路的尽头',//20
    '鼓楼大街',//21
    '摩天轮',//22///玩
    '过山车',//23
    '滑雪',//24
    '浮潜',//25
    '大摆锤', //26
    '长城', //27
    '上海', //28
    '东方明珠', //29
    '迪士尼', //30
    '甜食', //31
    '条头糕薄荷糕', //32
    '南翔小笼包', //33
    '上海国际服装文化节', //34
    '上海国际花卉节', //35
    '上海国际茶文化节',  //36
    '上海艺术博览会' ,  //37
    '外滩' ,  //38
    '城隍庙', //39
    '上海博物馆', //40
    '海盗船'  //41
];


//把搜索框中的字符串 拆分数组
//泼水节->[泼,泼水,泼水节,水节,节]
//最开始的方法是这样实现的,reviewCode后发现了更好的实现方法,在后面
function str2arrOld(d){
    let Arr = d.split('');
    let length = Arr.length;

    //组合 的等待搜索 的字符串 的数组
    let readySearch = [];
    for(let i in Arr){
        readySearch.push(Arr[i]);
        let j=i;
        let str = Arr[j];
        while(j<length-1){
            j++;
            str += Arr[j];
            readySearch.push(str);
        }
    }
    return readySearch;
}

//把搜索框中的字符串 拆分数组
//泼水节->[泼,泼水,泼水节,水节,节]
//这个方法这样实现更好,自己体会
function str2arr(d){
    let Arr = d.split('');
    let length = Arr.length;

    //组合 的等待搜索 的字符串 的数组
    let readySearch = [];
    for(let i in Arr){
        if(!Arr.hasOwnProperty(i)){
            continue;
        }
        let j=i;
        let str = '';
        while(j<length){
            str += Arr[j];
            readySearch.push(str);
            j++;
        }
    }
    return readySearch;
}

/**
 在tags里面搜索
 //搜索思路:
 //拆分数组当做 栈使用,长的元素先出栈->拆分的数组中的 元素 由短到长排序
 //遍历tags,每次遍历都把栈重置,每一次弹出栈中的元素 去匹配 tags中的每个元素
 //没命中,接着弹栈直到栈为空
 //命中了要做以下事情
 (比如,泼水节,命中了,那么[泼,水,节,泼水,水节,泼水节],都应该从最原始的栈中移除,防止搜出多于的结果,比如,泼水节,已经命中了,那么,节,还会命中其他的节日,比如,端午节,儿童节等,这显然不是我们想要的,所以要移除)
 //1.把弹出的元素,拆分成数组(比如,泼水节->[泼,泼水,泼水节,水,水节,节],我们叫arr防止混淆),然后从原始栈中移除 这个数组(arr)中的所有元素
 //2.把命中的键值放到要返回的数组中
 //3.退出while循环,匹配tags中的下一个


 //这里面有个问题:(此方法改为Old新的 改善的 方法写在后面)
 tags循环在readySearch外面是不合理的,
 比如,tags里有[泼水节,端午节,儿童节],
 此时,搜'端午节',拆分出的数组是[端,午,节,端午,午节,端午节]
 for(tags):
    1.泼水节
        while(readySearch):
        端午节x
        午节x
        端午x
        节 o->这个时候命中了,但是,命中的是'泼水节',显然是不想要的
 **/
function searchOld(d){

    //获得拆分之后的数组
    let readySearch = str2arr(d);

    //如果数组长度是0,就不做什么操作
    if(readySearch.length === 0){
        return false;
    }

    //数组排序,元素长的在后面
    //TODO error:这里的排序,这对>做了处理,else应该返回-1,与其这样,还不如 return a.length-b.length
    // let arrSorted = readySearch.sort(function(a,b){ if(a.length>b.length) return 1;});
    let arrSorted = readySearch.sort(function(a,b){return a.length-b.length;});

    //所搜结果数组
    let resArr = [];
    let resKey = [];
    for(let j in tags){
        if(!tags.hasOwnProperty(j)){
            return false;
        }

        //key已经存在了直接跳过
        if(resKey.indexOf(j)!== -1){
            continue;
        }

        // console.log('现在的数组是:--');
        // console.log(arrSorted);
        let tempArr = arrSorted.slice(0);
        if(tempArr.length === 0){
            continue;
        }

        while(tempArr.length>0){
            let str = tempArr.pop();
            // console.log(str);

            // console.log(`find '${str}' from '${tags[j]}' result is '${temp}'`);
            //没有找到找下一个,先搜的都是长的
            if(tags[j].indexOf(str) === -1){
                continue;
            }

            //找到了
            //移除重复的搜索,防止重复,
            //比如,成都,搜到了,那么,'成','都',肯定也都能搜到,所以,成,都,就都去掉就好了,就不要再搜索了

            // console.log(tags[j]);
            //拆出数组
            let removeArr = str2arr(str);
            for(let i of removeArr){
                // console.log(i);
                // console.log('从数组中移除了:-'+arrSorted[arrSorted.indexOf(i)]);
                let index = arrSorted.indexOf(i);

                //移除元素
                //TODO error:index等于-1的时候,移除的是最后一个元素,要加判断
                arrSorted.splice(index,1);
            }

            resKey.push(j);
            resArr.push(tags[j]);

            //如果命中的元素长度大于 str的长度,那么不应该移除自己本身,这里再加回去
            //TODO error:等于也要加进去,不然,[端午,端午节]搜端午,'端午'搜出来了,移除掉了,后面的端午节就出不来了
            if(tags[j].length>str.length){
                arrSorted.push(str);
            }

            //命中了就退出while循环,匹配tags中的下一个
            break;

        }
    }

    return {'value':resArr,'key':resKey};
}

//改善后的search
function search(d){

    //获得拆分之后的数组
    let readySearch = str2arr(d);

    //如果数组长度是0,就不做什么操作
    if(readySearch.length === 0){
        return false;
    }

    //数组排序,元素长的在后面
    let arrSorted = readySearch.sort(function(a,b){return a.length-b.length;});
    //所搜结果数组
    let resArr = [];
    let resKey = [];
    while(arrSorted.length>0){
        let str = arrSorted.pop();

        for(let j in tags){
            if(!tags.hasOwnProperty(j)){
                return false;
            }

            //key已经存在了直接跳过
            if(resKey.indexOf(j)!== -1){
                continue;
            }

            // console.log(str);

            // console.log(`find '${str}' from '${tags[j]}' result is '${temp}'`);
            //没有找到找下一个,先搜的都是长的
            if(tags[j].indexOf(str) === -1){
                continue;
            }

            //找到了
            //移除重复的搜索,防止重复,
            //比如,成都,搜到了,那么,'成','都',肯定也都能搜到,所以,成,都,就都去掉就好了,就不要再搜索了

            // console.log(tags[j]);
            //拆出数组
            let removeArr = str2arr(str);
            for(let i of removeArr){
                // console.log(i);
                // console.log('从数组中移除了:-'+arrSorted[arrSorted.indexOf(i)]);
                let index = arrSorted.indexOf(i);

                //移除元素
                if(index!==-1){
                    arrSorted.splice(index,1);
                }
            }

            resKey.push(j);
            resArr.push(tags[j]);

            //如果命中的元素长度大于 str的长度,那么不应该移除自己本身,这里再加回去
            if(tags[j].length>=str.length){
                arrSorted.push(str);
            }

            //命中了就退出while循环,匹配tags中的下一个
            break;

        }
    }

    return {'value':resArr,'key':resKey};
}

let string = '成泼';
search(string);