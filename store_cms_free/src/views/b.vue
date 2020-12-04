<template>
  <div id="root"> 
    <div :class="['red', 'bold']">数组绑定多个class</div>
    <div :class="[{red: isActive}, 'f30']" @click="isActive=!isActive">点我变色</div>
    <div :class="[showRed(), 'bold']">数组包含方法绑定class</div>
    <br/>
    <div :style="[red_f30, weight]">数组绑定多个变量style--<span @click="fun_x()">点我</span></div>
    <div :style="[red_f30,mx()]">数组包含变量+方法绑定style</div> 
  </div>
</template>

<script>
export default {
  data() {
    return {
      isActive: false,
      red_f30: {
        color: "red",
        fontSize:"30px"
      },
      weight: {
        fontWeight: "bold"
      }
    };
  },
  methods: {
    showRed() {
      return "blue";
    },
    mx() {
      return {
        ...this.weight,
        fontSize: "20px"
      };
    },
    fun_x(){
      const yellow={
        color: "yellow",
        fontSize:"22px"
      }
      this.red_f30=yellow
    }
  }
};
</script> 

<style scoped>
#root{
  line-height: 50px;
}
.red{
  color:red; 
}
.blue{
  color:cornflowerblue;
}
.bold{
  font-weight: bold;
}
.f30{
  font-size:30px;
}
</style>