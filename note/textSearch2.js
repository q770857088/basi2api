/**
 * 多关键词模糊搜索
 * @author:王迪<wangxiaoxingfu@gmail.com>
 * @description:实现字符串中的多个关键词的模糊查找,
 * 比如,数组中有成都和泼水节,搜索'成泼水' 结果会是:'成都,泼水节';
 * @mind 思路,遍历tags,每个元素中包含关键词的个数,最后将包含关键词最多的排在前面
 * */
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

let str = '上海文化节茶';


function search(str) {
    if(tags.indexOf((str))!==-1){
        console.log('找到了:');
        //找到了完全匹配的
        console.log(str);
        return true;
    }

    let temp =[];

    for(let i in tags){
        if(!tags.hasOwnProperty(i)){
            continue;
        }
        let tempStr = tags[i];

        let arr = tempStr.split('');

        let tempArr = [];
        tempArr['pos'] = [];
        tempArr['value'] = tags[i];
        tempArr['key'] = i;
        for(let j in arr){
            let index = str.indexOf(arr[j]);
            if(index === -1){
                continue;
            }
            tempArr['pos'].push(j);
        }

        if(tempArr['pos'].length ===0)
            continue;

        temp.push(tempArr);
    }

    return temp;
}

function abc(s){
    let resArr = search(s);

    let log = resArr.sort(function(a,b){
        return b['pos'].length - a['pos'].length;
    }).slice(0,5);

    console.log(log);
}

abc(str);