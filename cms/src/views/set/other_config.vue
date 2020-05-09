<template>
  <div class="order_list">
    <el-container class="container">
      <el-aside width="160px" class="not-print bg-white erbian">
        <Nav ac="1-3"></Nav>
      </el-aside>
      <el-main class="main">
        <template>
          <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>设置</el-breadcrumb-item>
            <el-breadcrumb-item>其他功能</el-breadcrumb-item>
          </el-breadcrumb>
          <div class="H10"></div>
          <div class="zhang">
            <el-tabs
              v-model="activeName"
              type="card"
              @tab-click="handleClick"
              style="background-color: #FFFFFF;"
            >
              <el-tab-pane label="短信配置" :name="3"></el-tab-pane>
              <el-tab-pane label="快递配置" :name="4"></el-tab-pane>
              <el-tab-pane label="OSS配置"  :name="5"></el-tab-pane>
              <el-tab-pane label="打印配置" :name="6"></el-tab-pane>
              <el-tab-pane label="水印配置" :name="7"></el-tab-pane>
              <el-tab-pane label="地图配置" :name="8"></el-tab-pane>
            </el-tabs>
            <el-form ref="form" label-width="160px">
              <el-form-item v-for="(item,index) in list" :label="item.desc" style="width: 70%">
                <template v-if="item.switch == 1">
                  <el-switch v-model="item.value" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
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
    Nav
  },
  data() {
    return {
      activeName: 3,
      list: []
    };
  },
   //vue生命函数
  mounted() {
    this.post_config(this.activeName);
  },
  methods: {
    handleClick(tab) {
		const tid=tab.name;
	  	this.activeName=tid
	  	this.post_config(tid) 
    },
    onSubmit() {
      var that = this;
      this.http.post_show("cms/edit_config", that.list).then(res => {
        that.$message({
          showClose: true,
          message: "修改成功",
          type: "success"
        });
      });
    },
    post_config(tid) {
      var that = this;
      this.http
        .post("cms/get_config", {
          type: tid
        })
        .then(res => {
          that.list = res.data; //收藏返回的是商品和店铺
        });
    }
  }
 
};
</script>

<style lang="less">
</style>
