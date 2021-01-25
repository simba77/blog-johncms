(window.webpackJsonp=window.webpackJsonp||[]).push([[0],{12:function(t,e,s){s(13),t.exports=s(41)},13:function(t,e,s){window.Vue=s(14),window.axios=s(18),window.axios.defaults.headers.common["X-Requested-With"]="XMLHttpRequest";s(36);Vue.component("pagination",s(38)),Vue.component("article-likes",s(39).default),Vue.component("blog-comments",s(40).default);new Vue({el:"#blog"})},39:function(t,e,s){"use strict";s.r(e);var a={name:"ArticleLikesComponent",props:{article_id:Number,rating:{type:Number,default:0},can_vote:{type:Boolean,default:!1},voted:{type:Number,default:0}},data:function(){return{message:"",loading:!1}},computed:{rating_color:function(){var t="";return this.rating>0?t="text-success":this.rating<0&&(t="text-danger"),t}},methods:{setVote:function(t){var e=this;this.loading=!0,axios.get("/blog/?action=set_vote&type_vote="+t+"&article_id="+this.article_id).then((function(t){e.rating=t.data.rating,e.voted=t.data.voted,e.message=t.data.message,e.loading=!1})).catch((function(t){alert(t),e.loading=!1}))}}},n=s(2),i=Object(n.a)(a,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"position-relative"},[t.loading?s("div",{staticClass:"d-flex justify-content-center position-absolute w-100 vote-preloader"},[t._m(0)]):t._e(),t._v(" "),s("button",{staticClass:"btn btn-light btn-sm",class:t.voted>0?"liked":"",attrs:{disabled:t.voted>0||!t.can_vote},on:{click:function(e){return t.setVote(1)}}},[s("svg",{staticClass:"icon download-button-icon mt-n1"},[s("use",{attrs:{"xlink:href":"/themes/default/assets/icons/sprite.svg#like"}})])]),t._v(" "),s("span",{staticClass:"ml-2 mr-2 font-weight-bold",class:t.rating_color},[t._v(t._s(t.rating>0?"+":"")+t._s(t.rating))]),t._v(" "),s("button",{staticClass:"btn btn-light btn-sm",class:t.voted<0?"disliked":"",attrs:{disabled:t.voted<0||!t.can_vote},on:{click:function(e){return t.setVote(-1)}}},[s("svg",{staticClass:"icon download-button-icon mr-1"},[s("use",{attrs:{"xlink:href":"/themes/default/assets/icons/sprite.svg#dislike"}})])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"spinner-border text-secondary",attrs:{role:"status"}},[e("span",{staticClass:"sr-only"},[this._v("Loading...")])])}],!1,null,null,null);e.default=i.exports},40:function(t,e,s){"use strict";s.r(e);var a={name:"CommentsComponent",props:{article_id:Number,can_write:{type:Boolean,default:!1},i18n:{type:Object,default:function(){return{write_comment:"Write a comment",send:"Send",delete:"Delete",quote:"Quote",reply:"Reply",comments:"Comments",empty_list:"The list is empty"}}}},data:function(){return{messages:{},comment_text:"",comment_added_message:"",error_message:"",loading:!1}},mounted:function(){this.getComments()},computed:{},methods:{getComments:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.loading=!0,axios.get("/blog/?action=comments&article_id="+this.article_id+"&page="+e).then((function(e){t.messages=e.data,t.loading=!1})).catch((function(e){alert(e),t.loading=!1}))},reply:function(t){this.comment_text="[b]"+t.user.user_name+"[/b], ",$("#comment_text").focus()},quote:function(t){var e=t.text.replace(/(<([^>]+)>)/gi,"");this.comment_text="[quote][b]"+t.user.user_name+"[/b] "+t.created_at+t.id+"\n"+e+"[/quote]",$("#comment_text").focus()},sendComment:function(){var t=this;this.loading=!0,axios.post("/blog/?action=add_comment&article_id="+this.article_id,{comment:this.comment_text}).then((function(e){t.comment_added_message=e.data.message,t.loading=!1,t.comment_text="",t.error_message="",t.getComments(e.data.last_page)})).catch((function(e){t.error_message=e.response.data.message,t.loading=!1}))},delComment:function(t){var e=this;this.loading=!0,axios.post("/blog/?action=del_comment",{comment_id:t}).then((function(t){e.getComments(e.messages.current_page)})).catch((function(t){alert(t.response.data.message),e.loading=!1}))},__:function(t){return _.get(this.i18n,t,"")}}},n=s(2),i=Object(n.a)(a,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"mt-4"},[s("h3",{staticClass:"font-weight-bold"},[t._v(t._s(t.__("comments"))+" "),t.messages.total>0?s("span",{staticClass:"text-success"},[t._v(t._s(t.messages.total))]):t._e()]),t._v(" "),t.messages.data&&t.messages.data.length<1?s("div",{staticClass:"alert alert-info"},[t._v(t._s(t.__("empty_list")))]):t._e(),t._v(" "),t._l(t.messages.data,(function(e){return s("div",{staticClass:"blog-comment"},[s("div",{staticClass:"new_post-header d-flex justify-content-between"},[s("div",{staticClass:"post-user"},[e.user.profile_url?s("a",{attrs:{href:e.user.profile_url}},[s("div",{staticClass:"avatar"},[s("img",{staticClass:"img-fluid",attrs:{src:e.user.avatar,alt:"."}})])]):t._e(),t._v(" "),s("span",{staticClass:"user-status shadow",class:e.user.is_online?"online":"offline"}),t._v(" "),e.user.rights_name?s("div",{staticClass:"post-of-user",attrs:{"data-toggle":"tooltip","data-placement":"top","data-html":"true",title:e.user.rights_name}},[s("svg",{staticClass:"icon-post"},[s("use",{attrs:{"xlink:href":"/themes/default/assets/icons/sprite.svg?#check"}})])]):t._e()]),t._v(" "),s("div",{staticClass:"flex-grow-1 post-user d-flex flex-wrap overflow-hidden d-flex align-items-center"},[s("div",{staticClass:"w-100"},[e.user.profile_url?s("a",{attrs:{href:e.user.profile_url}},[s("span",{staticClass:"user-name d-inline mr-2"},[t._v(t._s(e.user.user_name))])]):t._e(),t._v(" "),e.user.profile_url?t._e():s("div",{staticClass:"user-name d-inline mr-2"},[t._v(t._s(e.user.user_name))]),t._v(" "),s("span",{staticClass:"post-meta d-inline mr-2",attrs:{"data-toggle":"tooltip","data-placement":"top",title:"Link to post"}},[t._v("\n                        "+t._s(e.created_at)+"\n                    ")])]),t._v(" "),e.user.status?s("div",{staticClass:"overflow-hidden text-nowrap text-dark-brown overflow-ellipsis small"},[s("span",{staticClass:"font-weight-bold"},[t._v(t._s(e.user.status))])]):t._e()])]),t._v(" "),s("div",{staticClass:"post-body pt-2 pb-2",domProps:{innerHTML:t._s(e.text)}}),t._v(" "),s("div",{staticClass:"post-footer d-flex justify-content-between"},[s("div",{staticClass:"overflow-hidden"},[e.ip?s("div",{staticClass:"post-meta d-flex"},[s("div",{staticClass:"user-ip mr-2"},[s("a",{attrs:{href:e.search_ip_url}},[t._v(t._s(e.ip))])]),t._v(" "),s("div",{staticClass:"useragent"},[s("span",[t._v(t._s(e.user_agent))])])]):t._e()]),t._v(" "),s("div",{staticClass:"d-flex"},[e.can_reply?s("div",{staticClass:"ml-3"},[s("a",{attrs:{href:"#"},on:{click:function(s){return s.preventDefault(),t.reply(e)}}},[t._v(t._s(t.__("reply")))])]):t._e(),t._v(" "),e.can_quote?s("div",{staticClass:"ml-3"},[s("a",{attrs:{href:"#"},on:{click:function(s){return s.preventDefault(),t.quote(e)}}},[t._v(t._s(t.__("quote")))])]):t._e(),t._v(" "),e.can_delete?s("div",{staticClass:"dropdown ml-3"},[s("div",{staticClass:"cursor-pointer",attrs:{"data-toggle":"dropdown","aria-haspopup":"true","aria-expanded":"false"}},[s("svg",{staticClass:"icon text-primary"},[s("use",{attrs:{"xlink:href":"/themes/default/assets/icons/sprite.svg?#more_horizontal"}})])]),t._v(" "),s("div",{staticClass:"dropdown-menu dropdown-menu-right"},[s("a",{staticClass:"dropdown-item",attrs:{href:""},on:{click:function(s){return s.preventDefault(),t.delComment(e.id)}}},[t._v(t._s(t.__("delete")))])])]):t._e()])])])})),t._v(" "),s("pagination",{staticClass:"mt-3",attrs:{data:t.messages},on:{"pagination-change-page":t.getComments}}),t._v(" "),t.can_write?s("div",{staticClass:"mt-4"},[s("h3",{staticClass:"font-weight-bold"},[t._v(t._s(t.__("write_comment")))]),t._v(" "),s("form",{attrs:{action:""},on:{submit:function(e){return e.preventDefault(),t.sendComment(e)}}},[t.error_message?s("div",{staticClass:"d-flex"},[s("div",{staticClass:"alert alert-danger d-inline"},[t._v(t._s(t.error_message))])]):t._e(),t._v(" "),t.comment_added_message?s("div",{staticClass:"d-flex"},[s("div",{staticClass:"alert alert-success d-inline"},[t._v(t._s(t.comment_added_message))])]):t._e(),t._v(" "),s("div",{staticStyle:{"max-width":"800px"}},[s("textarea",{directives:[{name:"model",rawName:"v-model",value:t.comment_text,expression:"comment_text"}],staticClass:"form-control",attrs:{name:"text",id:"comment_text",rows:"6",required:""},domProps:{value:t.comment_text},on:{input:function(e){e.target.composing||(t.comment_text=e.target.value)}}})]),t._v(" "),s("div",{staticClass:"mt-2"},[s("button",{staticClass:"btn btn-primary",attrs:{type:"submit",name:"submit",value:"1",disabled:t.loading}},[t.loading?s("span",{staticClass:"spinner-border spinner-border-sm",attrs:{role:"status","aria-hidden":"true"}}):t._e(),t._v("\n                    "+t._s(t.__("send"))+"\n                ")]),t._v(" "),s("div")])])]):t._e()],2)}),[],!1,null,null,null);e.default=i.exports},41:function(t,e){}},[[12,1,2]]]);
//# sourceMappingURL=blog.js.map