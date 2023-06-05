<!DOCTYPE html>
<?php require('../static/utils/allFunc.php') ?>
<?php if (checkLogin()) {
    header("location:index.php");
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <!-- import CSS -->
    <!--<link rel="stylesheet" href="../static/css/element-ui.css">-->
    <link rel="stylesheet" href="https://unpkg.com/element-ui@2.9.1/lib/theme-chalk/index.css">
    <style type="text/css">
        .login {
            width: 400px;
            height: 350px;
            margin: 80px auto;
        }
    </style>
    <title>网上小商城登录</title>
</head>
<body>
<div id="app">
    <div class="login">
        <el-tabs v-model="formStatus" type="border-card" style="border-radius: 15px;" @tab-click="handleChange">
            <el-tab-pane label="登录" name="signIn">
                <el-form :model="form" label-width="60px" :rules="rules" ref="ruleForm" hide-required-asterisk>
                    <el-form-item label="账号:" prop="account">
                        <el-input type="text" v-model="form.account"></el-input>
                    </el-form-item>
                    <el-form-item label="密码:" prop="password">
                        <el-input type="password" v-model="form.password"></el-input>
                    </el-form-item>
                    <el-form-item label="验证码:" prop="captcha">
                        <el-input type="text" v-model="form.captcha"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-image :src="captcha" @click="getCaptcha"></el-image>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="signIn">登录</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
            <el-tab-pane label="注册" name="signUp">
                <el-form :model="form" label-width="70px" :rules="rules" ref="ruleForm">
                    <el-form-item label="账号:" prop="account">
                        <el-input type="text" v-model="form.account"></el-input>
                    </el-form-item>
                    <el-form-item label="密码:" prop="password">
                        <el-input type="password" show-password v-model="form.password"></el-input>
                    </el-form-item>
                    <el-form-item label="验证码:" prop="captcha">
                        <el-input type="text" v-model="form.captcha"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-image :src="captcha" @click="getCaptcha"></el-image>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="signUp">注册</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
        </el-tabs>
    </div>
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
                rules: {
                    account: [
                        {required: true, message: '请输入账号', trigger: 'blur'}
                    ], password: [
                        {required: true, message: '请输入密码', trigger: 'blur'}
                    ], captcha: [
                        {required: true, message: '请输入验证码', trigger: 'blur'}
                    ]
                },
                formStatus: 'signIn',
                form: {
                    account: '',
                    password: '',
                    captcha: '',
                },
                checkPass: '',
                captcha: ''
            }
        }, methods: {
            handleChange() {
                this.getCaptcha();
                this.form.password = '';
                this.form.captcha = '';
            },
            signIn() {
                let that = this;
                this.$refs['ruleForm'].validate((valid) => {
                    if (valid) {
                        axios.post('../dao/signIn.php', Qs.stringify(that.form))
                            .then(function (res) {
                                if (res.data === 'success') {
                                    that.$message.success('登录成功');
                                    that.form.account = '';
                                    that.form.password = '';
                                    window.location.href = "index.php";
                                } else if (res.data === 'login_error') {
                                    that.getCaptcha();
                                    that.$message.error('用户名或密码错误');
                                } else if (res.data === 'login_cnt_error') {
                                    that.getCaptcha();
                                    that.$message.error('登录失败次数过多');
                                } else if (res.data === 'captcha_error') {
                                    that.$message.error('验证码错误');
                                    that.form.password = '';
                                    that.form.captcha ='';
                                    that.getCaptcha();
                                } else {
                                    that.$message.error('系统错误');
                                }
                            })
                            .catch(function (err) {
                                console.log(err);
                            });
                    } else {
                        return false;
                    }
                });

            },
            signUp() {
                let that = this;
                this.$refs['ruleForm'].validate((valid) => {
                    if (valid) {
                        axios.post('../dao/signUp.php', Qs.stringify(that.form))
                            .then(function (res) {
                                if (res.data === 'success') {
                                    that.getCaptcha();
                                    that.$message.success('注册成功,请登录');
                                    that.form.password = '';
                                    that.form.captcha = '';
                                    that.formStatus='signIn';
                                } else if (res.data === 'user_undefined') {
                                    that.getCaptcha();
                                    that.$message.error("该用户名已使用");
                                } else if (res.data === 'captcha_error') {
                                    that.$message.error('验证码错误');
                                    that.form.password = '';
                                    that.form.captcha ='';
                                    that.getCaptcha();
                                } else {
                                    that.getCaptcha();
                                    that.$message.error('系统错误');
                                }
                            })
                            .catch(function (err) {
                                console.log(err);
                            });
                    } else {
                        return false;
                    }
                });
            },
            getCaptcha() {
                let that = this;
                axios.get('../dao/captcha.php', {responseType: 'arraybuffer'})
                    .then(function (data) {
                        that.captcha = 'data:image/png;base64,' + btoa(new Uint8Array(data.data).reduce((data, byte) => data + String.fromCharCode(byte), ''))
                    })
                    .catch(function (err) {
                        console.log(err);
                    });
            },
        },
        created() {
            this.getCaptcha();
        }
    })
</script>
</html>