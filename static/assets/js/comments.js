/*
	这就是该模块的入口文件
*/

// 1 配置模块
// require.config({});
require.config({
    // 1.1 声明模块
    // 一共要声明的模块有： jquery, 模板引擎, 分页插件, bootstrap
    paths: { //作用：声明每个模块的名称和每个模块对应的路径
        //模块的名字 ： 模块对应的js的路径 - 注意路径是不带后缀名的
        "jquery": "/static/assets/vendors/jquery/jquery",
        "bootstrap": "/static/assets/vendors/bootstrap/js/bootstrap",

    },
    // 1.2 声明模块和模块之间的依赖关系
    shim: {
        //模块名称
        "bootstrap": {
            deps: ["jquery"]
        }
    }
});

// 2 引入模块
// 使用 requirejs 提供的一个函数来实现
// require(模块数组， 实现功能的回调函数)
// 模块数组中的每个模块的名字是从 paths声明的时候那里直接得到的
require(["jquery", "bootstrap"], function($, bootstrap) {
    // 3 在回调函数中实现功能
    $(function() {
        var currentPage = 1;
        var pageSize = 10;
        var status = { held: '待审核', approved: '准许', rejected: '拒绝', trashed: '回收站' };

        function makeTable(data) {
            var str = '';
            $.each(data, function(indexInArray, e) {
                str += `<tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>${e.author}</td>
            <td>${e.content}</td>
            <td>${e.title}</td>
            <td>${e.created}</td>
            <td>${status[e.status]}</td>
            <td class="text-center">
              <a href="post-add.php" class="btn btn-warning btn-xs">驳回</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>`;
            })
            $("tbody").html(str);
        }

        function makePaging(totalPage) {
            var start = currentPage - 2;
            if (start <= 0) {
                start = 1;
            }
            var end = start + 4;
            var maxPage = Math.ceil(totalPage / pageSize);
            if (end > maxPage) {
                end = maxPage;
            }
            var html = '';
            if (currentPage != 1) {
                html += '<li><a href="javascript:;">上一页</a></li>';
            } else {
                html = "";
            }
            for (let i = start; i <= end; i++) {
                if (i == currentPage) {
                    html += '<li class="items active" data-page="' + i + '"><a href="javascript:;">' + i + '</a></li>';
                } else {
                    html += '<li class="items" data-page="' + i + '"><a href="javascript:;">' + i + '</a></li>';
                }
            }
            if (currentPage != end) {
                html += '<li><a href="javascript:;">下一页</a></li>';
            }
            $(".pagination").html(html);
        }
        $.ajax({
            type: "post",
            url: "api/_getCommentsData.php",
            data: { currentPage: currentPage, pageSize: pageSize },
            success: function(response) {
                makeTable(response.data);
                makePaging(response.totalPage);
            }
        });
        $(".pagination").on("click", ".items", function() {
            currentPage = $(this).attr("data-page");
            $.ajax({
                type: "post",
                url: "api/_getCommentsData.php",
                data: { currentPage: currentPage, pageSize: pageSize },
                success: function(response) {
                    makePaging(response.totalPage);
                    makeTable(response.data);
                }
            });
        })
    })
});