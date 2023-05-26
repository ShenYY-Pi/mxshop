<el-header height="60px">
    <div class="userInfo">
        <el-link :underline="false" href="index.php" style="color: #fff;margin-right: 15px;">首页</el-link>
        <?php
        if (checkLogin()) {
            echo '<el-link :underline="false" href="person.php" style="color: #fff;">' . getUserName() . '</el-link>';
            echo " 你好";
            if(getUserRole()==2){
                echo '<span style="width: 50px;"><el-link :underline="false" href="admin/index.php" style="color: #fff;margin-left: 20px;">管理界面</el-link></span>';
            }
            echo '<span class="banner">' .
                '<el-link :underline="false" href="cart.php" style="color: #fff;">查看购物车</el-link>' .
                '<el-link :underline="false" href="order.php" style="color: #fff;margin-left: 5px;">查看订单</el-link>' .
                '<el-link :underline="false" href="logout.php" style="color: #fff;margin-left: 5px;">退出</el-link>' .
                '</span>';
        } else {
            echo '<el-link :underline="false" href="login.php" style="color: #fff;">登录</el-link>';
        }
        ?>
    </div>
</el-header>