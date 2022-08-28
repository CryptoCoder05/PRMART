new Vue({
  el:'#vue-class',
  data:{
    root_folder: 'Anirudh singh',
  }
});




new Vue({
  // element with selector-----------------------------
  el:'#vue-t1',
  //properties: data------------------------------------
  data:{
    Name: 'Anirudh singh',
    user: 'Customer Side',
    admin: 'Admin Side',
    web: 'https://www.youtube.com',
    webTag: '<p>This is html content binding</p>',
    counter: 0,
    x:0,
    y:0,
    char:'',
    firstName:'',
    lastName: '',
    update: 'Updated Name',
    meter: '',
    a:0,
    b:0,
    isActive: true,
  },
//properties: methods-------------------------------------
  methods:{
     greet: function(time) {
       return 'Good' + ' ' + time +' '+ this.Name;
     },

     increment: function(inc) {
        this.counter = this.counter + inc;
     },

     decrement: function (dec) {
        this.counter = this.counter - dec;
     },

     movefunction: function (event) {
        this.x = event.offsetX;
        this.y = event.offsetY;
     },

     overfunction:function() {
        alert('mouseOver');
     },

     outfunction: function() {
        alert('MouseOut');
     },

     keyfunction: function(event) {
       console.log(event);
     },
      parentclick: function () {
        console.log("Parent click");
      },

      childclick: function () {
        console.log("Child click");
      },

      toggleClass: function () {
        this.isActive =! this.isActive;
      },

      refFunction:function () {
        console.log(this.$refs.firstName.value);
        console.log(this.$refs.lastName.value);
      }
  },
//properties: computed----------------------------
  computed: {
    getFullName:{
      get: function () {
        return this.firstName + ' ' + this.lastName;
      },
      set: function () {
        return this.update = 'FullName updated';
      }
    },


    convertTocentimeter: function () {
      return this.meter*100+'cm';
    },

    sum: function () {
      return parseInt(this.a) + parseInt(this.b);
    }
  }

});
