<!DOCTYPE html>
<?php require('../static/utils/allFunc.php') ?>
<?php
    if(!checkLogin()){
        header("location:login.php");
    }
?>
<html>
<head>
    <meta charset="UTF-8">
    <!-- import CSS -->
    <!--<link rel="stylesheet" href="../static/css/element-ui.css">-->
    <link rel="stylesheet" href="https://unpkg.com/element-ui@2.9.1/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="../static/css/header.css">
    <title>MxShop 用户信息</title>
</head>
<body>
<div id="app">
    <el-container>
        <?php require_once('./components/header.php') ?>
        <el-main style="margin-left: 150px;">
            修改信息
            <el-form>
                <el-form-item label="用户昵称" prop="names">
                    <el-input v-model="name" style="width: 300px;"></el-input>
                    <el-button class="ml10" type="primary" @click="modifyName">修改</el-button>
                </el-form-item>
            </el-form>
        </el-main>
    </el-container>
</div>
</body>
<!--<script src="../static/js/vue.js"></script>-->
<!--<script src="../static/js/element-ui.js"></script>-->
<script src="https://unpkg.com/vue@2.6.10/dist/vue.js"></script>
<script src="https://unpkg.com/element-ui@2.9.1/lib/index.js"></script>
<script src="https://cdn.bootcss.com/axios/0.18.0/axios.js"></script>
<script src="https://cdn.bootcss.com/qs/6.7.0/qs.js"></script>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                name: '<?php echo getUserName(); ?>',
                userId: '<?php echo getUserId(); ?>',
            }
        }, methods: {
            modifyName() {
                if (this.name == '') {
                    this.$message.error('昵称不能为空');
                    return;
                }
                let that = this;
                let data = new URLSearchParams();
                data.append('name', that.name);
                axios.post('../dao/modifyName.php', data)
                    .then(function (res) {
                        if (res.data === 'success') {
                            that.$message.success('修改成功');
                            window.location.reload ();
                        } else {
                            that.$message.error('修改失败');
                        }
                    })
                    .catch(function (err) {
                        console.log(err);
                        that.$message.error('修改失败');
                    });
            }
        },
        created() {

        }
    })
</script>
</html>