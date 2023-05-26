<!DOCTYPE html>
<?php require('../../static/utils/allFunc.php') ?>
<?php
if (getUserRole() != 2) {
    header('location:../index.php');
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <!-- import CSS -->
    <!--<link rel="stylesheet" href="../static/css/element-ui.css">-->
    <link rel="stylesheet" href="https://unpkg.com/element-ui@2.9.1/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="../../static/css/header.css">
    <title>MxShop 后台管理</title>
</head>
<body>
<div id="app">
    <el-container>
        <el-header height="60px" style="background-color: #00425d;">
            <el-link href="../index.php" :underline="false" style="color: #fff;font-size: 26px;margin-top: 10px;">
                MxShop 后台管理
            </el-link>
        </el-header>
        <el-main>
            <el-tabs tab-position="left">
                <el-tab-pane label="商品管理">
                    <el-select v-model="tagValue" size="mini" @change="handleProductChange">
                        <el-option label="未分类" :value="-1"></el-option>
                        <el-option v-for="(item,index) in tagList"
                                   :key="index"
                                   :label="item.name"
                                   :value="item.id">
                        </el-option>
                    </el-select>
                    <el-button class="ml10" type="primary" size="mini" @click="handleProductAdd">添加商品</el-button>
                    <el-button type="primary" size="mini" @click="getProductList">刷新</el-button>
                    <el-table :data="productsList" v-loading="loading">
                        <el-table-column prop="image" label="图片" width="106px">
                            <template slot-scope="scope">
                                <el-popover placement="right-start"
                                            width="200"
                                            trigger="hover">
                                    <a target="_blank" :href="'../product.php?id='+scope.row.product_id"
                                       slot="reference">
                                        <el-image style="width:80px;height: 80px"
                                                  :src="'../../uploads/images/'+scope.row.image"
                                                  fit="cover"/>
                                    </a>
                                    <el-image style="width:200px;height: 200px"
                                              :src="'../../uploads/images/'+scope.row.image"
                                              fit="cover">
                                    </el-image>
                                </el-popover>
                            </template>
                        </el-table-column>
                        <el-table-column prop="name" label="名称" width="230px">
                            <template slot-scope="scope">
                                <el-link target="_blank" :href="'../product.php?id='+scope.row.product_id">
                                    {{scope.row.name}}
                                </el-link>
                            </template>
                        </el-table-column>
                        <el-table-column prop="price" label="价格" width="100px">
                            <template slot-scope="scope">
                                <span style="color: #ff5415;">￥{{scope.row.price}}</span>
                            </template>
                        </el-table-column>
                        <el-table-column prop="number" label="库存">
                            <template slot-scope="scope">
                                <span>库存:{{scope.row.number}}</span>
                            </template>
                        </el-table-column>
                        <el-table-column label="操作" width="150">
                            <template slot-scope="scope">
                                <el-button type="primary" size="mini" @click="handleProductEdit(scope.row)">编辑
                                </el-button>
                                <el-button type="danger" size="mini" @click="handleProductDelete(scope.row)">删除
                                </el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-tab-pane>
                <el-tab-pane label="分类管理">
                    <el-button type="primary" @click="handleAddTag" size="mini">添加</el-button>
                    <el-button type="primary" size="mini" @click="getTagList">刷新</el-button>
                    <el-table :data="tagList">
                        <el-table-column prop="name" label="名称">
                        </el-table-column>
                        <el-table-column>
                            <template slot-scope="scope">
                                <el-button type="primary" @click="handleEditTag(scope.row)" size="mini">编辑</el-button>
                                <!--删除分类将所有原分类商品添加至未分类-->
                                <el-button type="danger" @click="handleDeleteTag(scope.row)" size="mini">删除</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-tab-pane>
                <el-tab-pane label="用户管理">
                    <el-table :data="userList">
                        <el-table-column type="index" label="序号" width="50"></el-table-column>
                        <el-table-column prop="account" label="账号"></el-table-column>
                        <el-table-column prop="name" label="用户名"></el-table-column>
                        <el-table-column prop="role" label="用户组" :formatter="roleFormat" width="100"></el-table-column>
                        <el-table-column prop="created_time" label="创建时间"></el-table-column>
                        <el-table-column label="操作">
                            <template slot-scope="scope">
                                <el-button type="danger" @click="deleteUser" size="mini">删除用户</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-tab-pane>
            </el-tabs>
        </el-main>
        <el-dialog :visible.sync="productSwitch">
            <el-form :model="productForm" label-width="80px">
                <el-form-item label="商品分类">
                    <el-select v-model="productForm.product_classify_id">
                        <el-option v-for="(item,index) in tagList"
                                   :key="index"
                                   :label="item.name"
                                   :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="商品名称">
                    <el-input v-model="productForm.name"></el-input>
                </el-form-item>
                <el-form-item label="商品介绍">
                    <el-input v-model="productForm.content"></el-input>
                </el-form-item>
                <el-form-item label="商品价格">
                    <el-input v-model.double="productForm.price"></el-input>
                </el-form-item>
                <el-form-item label="商品库存">
                    <el-input v-model.number="productForm.number"></el-input>
                </el-form-item>
                <el-form-item label="商品小图">
                    <el-input v-model="productForm.image">
                    </el-input>
                </el-form-item>
                <el-form-item label="商品大图">
                    <el-input v-model="productForm.big_image">
                    </el-input>
                </el-form-item>
                <el-form-item v-if="!modifyProduct">
                    <el-button type="primary" @click="confirmAddProduct">添加</el-button>
                </el-form-item>
                <el-form-item v-else>
                    <el-button type="primary" @click="confirmEditProduct">确认修改</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
        <el-dialog :visible.sync="tagSwitch">
            <el-form label-width="80px" :model="tagForm">
                <el-form-item label="分类名称">
                    <el-input v-model="tagForm.name"></el-input>
                </el-form-item>
                <el-form-item v-if="!modifyTag">
                    <el-button type="primary" @click="confirmAddTag" size="mini">添加</el-button>
                </el-form-item>
                <el-form-item v-else>
                    <el-button type="primary" @click="confirmEditTag" size="mini">确认修改</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
    </el-container>
</div>
</body>
<!--<script src="../../static/js/vue.js"></script>-->
<!--<script src="../../static/js/element-ui.js"></script>-->
<script src="https://unpkg.com/vue@2.6.10/dist/vue.js"></script>
<script src="https://unpkg.com/element-ui@2.9.1/lib/index.js"></script>
<script src="https://cdn.bootcss.com/axios/0.18.0/axios.js"></script>
<script src="https://cdn.bootcss.com/qs/6.7.0/qs.js"></script>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: true,
                tagValue: -1,
                tagList: [],
                productsList: [],
                userList: [],
                productSwitch: false,
                modifyProduct: false,
                productForm: {
                    product_id: '',
                    product_classify_id: '',
                    name: '',
                    price: '',
                    number: '',
                    status: 1,
                    content: '',
                    image: '',
                    big_image: ''
                },
                tagForm: {
                    id: '',
                    name: ''
                },
                tagSwitch: false,
                modifyTag: false,
            }
        }, methods: {
            roleFormat(row, column) {
                return row.role == 2 ? '管理员' : '用户';
            },
            handleProductChange() {
                this.getProductList();
            },
            handleProductAdd() {
                this.productForm.product_id = null;
                this.productForm.product_classify_id = this.tagValue;
                this.productForm.name = null;
                this.productForm.price = null;
                this.productForm.number = null;
                this.productForm.status = null;
                this.productForm.content = null;
                this.productForm.image = null;
                this.productForm.big_image = null;
                this.productSwitch = true;
                this.modifyProduct = false;
            },
            handleProductEdit(row) {
                this.productSwitch = true;
                this.productForm.product_id = row.product_id;
                this.productForm.product_classify_id = row.product_classify_id;
                this.productForm.name = row.name;
                this.productForm.price = row.price;
                this.productForm.number = row.number;
                this.productForm.status = row.status;
                this.productForm.content = row.content;
                this.productForm.image = row.image;
                this.productForm.big_image = row.big_image;
                this.modifyProduct = true;
            },
            handleProductDelete(row) {
                let that = this;
                let data = new URLSearchParams();
                data.append('id', row.product_id);
                this.$confirm('确认删除该商品?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('../../dao/deleteProduct.php', data)
                        .then(function (res) {
                            if (res.data === 'success') {
                                that.$message.success('删除成功');
                                that.getProductList();
                            } else {
                                that.$message.error('错误');
                            }
                        })
                        .catch(function (err) {
                            that.$message.error('错误');
                            console.log(err);
                        });
                }).catch(() => {
                });
            },
            confirmAddProduct() {
                let that = this;
                axios.post('../../dao/addProduct.php', Qs.stringify(this.productForm))
                    .then(function (res) {
                        if (res.data === 'success') {
                            that.$message.success('添加成功');
                            that.getProductList();
                            that.productSwitch = false;
                        } else {
                            that.$message.error('错误');
                        }
                    })
                    .catch(function (err) {
                        that.$message.error('错误');
                        console.log(err);
                    });
            },
            confirmEditProduct() {
                // let data = new URLSearchParams();
                // data.append(this.productForm);
                let that = this;
                axios.post('../../dao/modifyProduct.php', Qs.stringify(this.productForm))
                    .then(function (res) {
                        if (res.data === 'success') {
                            that.$message.success('修改成功');
                            that.getProductList();
                            that.productSwitch = false;
                        } else {
                            that.$message.error('错误');
                        }
                    })
                    .catch(function (err) {
                        that.$message.error('错误');
                        console.log(err);
                    });
            },

            handleAddTag() {
                this.tagForm.name = '';
                this.modifyTag = false;
                this.tagSwitch = true;
            },
            handleEditTag(row) {
                this.tagForm.name = row.name;
                this.tagForm.id = row.id;
                this.modifyTag = true;
                this.tagSwitch = true;
            },
            handleDeleteTag(row) {
                let that = this;
                let data = new URLSearchParams();
                data.append('id', row.id);

                this.$confirm('确认删除该分类?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('../../dao/deleteTag.php', data)
                        .then(function (res) {
                            if (res.data === 'success') {
                                that.$message.success('删除成功');
                                that.getTagList();
                            } else {
                                that.$message.error('错误');
                            }
                        })
                        .catch(function (err) {
                            that.$message.error('错误');
                            console.log(err);
                        });
                }).catch(() => {
                });
            },
            deleteUser(row) {
                let that = this;
                let data = new URLSearchParams();
                data.append('id', row.id);

                this.$confirm('确认删除该用户?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('../../dao/deleteUser.php', data)
                        .then(function (res) {
                            if (res.data === 'success') {
                                that.$message.success('删除成功');
                                that.getUserList();
                            } else {
                                that.$message.error('错误');
                            }
                        })
                        .catch(function (err) {
                            that.$message.error('错误');
                            console.log(err);
                        });
                }).catch(() => {
                });
            },
            confirmAddTag() {
                let that = this;
                // console.log(this.tagForm.name);
                let data = new URLSearchParams();
                data.append('name', this.tagForm.name);
                axios.post('../../dao/addTag.php', data)
                    .then(function (res) {
                        if (res.data === 'success') {
                            that.$message.success('添加成功');
                            that.getTagList();
                            that.tagSwitch = false;
                        } else {
                            that.$message.error('错误');
                        }
                    })
                    .catch(function (err) {
                        that.$message.error('错误');
                        console.log(err);
                    });
            },
            confirmEditTag() {
                let that = this;
                let data = new URLSearchParams();
                data.append('name', this.tagForm.name);
                data.append('id', this.tagForm.id);
                axios.post('../../dao/modifyTag.php', data)
                    .then(function (res) {
                        if (res.data === 'success') {
                            that.$message.success('修改成功');
                            that.getTagList();
                            that.tagSwitch = false;
                        } else {
                            that.$message.error('错误');
                        }
                    })
                    .catch(function (err) {
                        that.$message.error('错误');
                        console.log(err);
                    });
            },
            getTagList() {
                let that = this;
                axios.get('../../dao/getTagList.php')
                    .then(function (res) {
                        // console.log(JSON.stringify(res.data));
                        if (res.data != 'error') {
                            that.tagList = res.data;
                            that.getProductList(that.tagValue);
                        }
                    })
                    .catch(function (err) {
                        console.log(err);
                    });
            },
            getProductList() {
                this.loading = true;
                let that = this;
                axios.get('../../dao/getProductList.php', {params: {'tagId': that.tagValue}}).then(function (res) {
                    if (res.data != 'error') {
                        that.productsList = res.data;
                        that.loading = false;
                    }
                }).catch(function (err) {
                    console.log(err);
                })
            },
            getUserList() {
                let that = this;
                axios.get('../../dao/getUserList.php').then(function (res) {
                    if (res.data != 'error') {
                        that.userList = res.data;
                    }
                }).catch(function (err) {
                    console.log(err);
                })
            }
        },
        created() {
            this.getTagList();
            this.getUserList();
        }
    })
</script>
</html>