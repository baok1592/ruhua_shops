<template>
  <div class="order_list">
    <el-container class="container">
      <el-aside width="160px" class="not-print bg-white erbian">
        <Nav ac="1-2"></Nav>
      </el-aside>
      <el-main class="main">
        <template>
          <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>设置</el-breadcrumb-item>
            <el-breadcrumb-item>支付配置</el-breadcrumb-item>
          </el-breadcrumb>
          <div class="H10"></div>
          <div class="zhang padd">
            <el-tabs
              v-model="activeName"
              type="card"
              @tab-click="handleClick"
              style="background-color: #FFFFFF;"
            >
              <el-tab-pane label="微信配置" :name="2"></el-tab-pane>
              <el-tab-pane label="支付宝配置" :name="9"></el-tab-pane>
            </el-tabs>

            <el-form ref="form" label-width="100px">
              <el-form-item v-for="(item,index) in list" :label="item.desc" style="width: 70%">
                <template v-if="item.switch == 1">
                  <el-switch v-model="item.switch" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
                </template>
                <template v-else>
                  <el-input v-model="item.value"></el-input>
                </template>
              </el-form-item>
              <el-form-item style="width: 80%">
                <el-button type="primary" size="medium" @click="onSubmit">提交修改</el-button>
              </el-form-item>
            </el-form>
          </div>
        </template>
      </el-main>
    </el-container>
  </div>
</template>

<script>
import Nav from "./components/set_nav.vue";
export default {
  components: {
    Nav,
  },
  data() {
    return {
	  list: [],
	  activeName:2
    };
  },
  //vue生命函数
  mounted() {
    this.post_config(this.activeName);
  },
  methods: {
    onSubmit() {
      var that = this;
      this.http.post_show("cms/edit_config", that.list).then((res) => {
        that.$message({
          showClose: true,
          message: "添加成功",
          type: "success",
        });
      });
    },
    handleClick(tab) {
      const tid = tab.name;
      this.activeName = tid;
      this.post_config(tid);
    },
    post_config(tid) {
      var that = this;
      this.http
        .post("cms/get_config", {
          type: tid,
        })
        .then((res) => {
          that.list = res.data; //收藏返回的是商品和店铺
        });
    },
  },
};
</script>

<style lang="less">
.el-form-item {
  margin-bottom: 12px !important;
}
.padd {
  padding: 50px 40px;
}
</style>
