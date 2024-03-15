import{j as L,D as U,i as u,o,e as i,a as e,t as r,f,b as h,w as x,g as v,F as p,h as g,k as E,G as B,J as F,n as y,l as w}from"./app-DeJr7fNP.js";import{a as O}from"./InputLabel-C2gVT93H.js";import{P as A,r as N}from"./Pagination-VNHzEPuv.js";import{r as z,I as V}from"./Footer-BYUJXMx0.js";import{_ as q}from"./PrimaryButton-Fk5jf8Nh.js";import{U as G}from"./UserSelector-D8GJCG_Y.js";import{_ as X}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{r as K}from"./MagnifyingGlassIcon--KPgqpVy.js";import{r as J}from"./CartItemButton-BUsVs_zz.js";import{r as R}from"./TrashIcon-BFmLih4l.js";const H={name:"OrderSelector",props:["id","mode","updateLink","preload","paginate","selecteds","placeholder","error","link","label","colsData","labelsData","callback"],components:{UserSelector:G,PrimaryButton:q,ChevronDownIcon:z,MagnifyingGlassIcon:K,Pagination:A,XMarkIcon:J,ArrowsUpDownIcon:N,Image:V,InputError:O,Link:L,TrashIcon:R},data(){return{params:{page:1,search:null,paginate:this.paginate||24,order_by:null,repo_id:null,status:"ready",dir:"DESC",before_selected_user_orders:[],before_selected_agency_orders:[]},Modal:null,data:[],pagination:{},loading:!1,removing:!1,uploading:!1,selectingForIndex:null,selectedText:null,selectedItem:null,errors:null}},emits:["update:selecteds"],created(){this.cols=this.colsData||["id","items","from_agency_id","from_repo_id"],this.labels=this.labelsData||["id","items","agency_id","repository"]},mounted(){this.callback;const t=document.getElementById(`modalOrders-${this.id}`);this.Modal=new U(t),this.preload&&this.preload.length>0&&this.$emit("update:selecteds",this.preload),this.$nextTick(()=>{this.initTableDropdowns()})},methods:{clear(){this.selectedItem=null,this.selectedText=null,this.$emit("update:selecteds",[])},removeItem(t,a){t.shipping_id?this.edit({cmnd:"remove-order",idx:a,id:t.shipping_id,order_id:t.id,order_type:t.type}):(t.type=="user"&&this.params.before_selected_user_orders.filter(l=>l!=t.id),t.type=="agency"&&this.params.before_selected_agency_orders.filter(l=>l!=t.id),this.selecteds.splice(a,1),this.$emit("update:selecteds",this.selecteds))},selectItem(t,a){this.selecteds.push(t),t.type=="user"&&this.params.before_selected_user_orders.push(t.id),t.type=="agency"&&this.params.before_selected_agency_orders.push(t.id),this.data.splice(a,1),this.$emit("update:selecteds",this.selecteds)},getData(){this.loading=!0,this.data=[],this.params.before_selected_user_orders=this.selecteds.filter(t=>t.type=="user").map(t=>t.id),this.params.before_selected_agency_orders=this.selecteds.filter(t=>t.type=="agency").map(t=>t.id),window.axios.get(this.link,{params:this.params},{}).then(t=>{this.data=t.data.data,delete t.data.data,this.pagination=t.data}).catch(t=>{t.response?this.errors=t.response.data:t.request?this.errors=t.request:this.errors=t.message}).finally(()=>{this.loading=!1})},edit(t){this.isLoading(!0),this.errors={},window.axios.patch(this.updateLink,t,{}).then(a=>{if(a.data&&(a.data.message?this.showToast("success",a.data.message):this.showToast("success",this.__("updated_successfully")),t.cmnd=="remove-order"))for(const l in this.preload)this.preload[l].id==t.order_id&&this.preload[l].type==t.order_type&&(this.preload.splice(l,1),this.removeItem(t,t.idx));this.selected=null}).catch(a=>{this.errorMessage=this.getErrors(a),a.response&&a.response.data&&(this.errors=a.response.data.errors||{}),this.showToast("danger",this.errorMessage)}).finally(()=>{this.isLoading(!1)})},editOrder(t){this.isLoading(!0),this.errors={},window.axios.patch(route(`admin.panel.order.${t.type}.update`),t,{}).then(a=>{a.data&&a.data.message&&this.showToast("success",a.data.message),a.data.status?(this.selecteds[t.idx].status=a.data.status,a.data.statuses&&(this.selecteds[t.idx].statuses=a.data.statuses)):this.getData(),this.selected=null}).catch(a=>{this.errorMessage=this.getErrors(a),a.response&&a.response.data&&(this.errors=a.response.data.errors||{}),this.showToast("danger",this.errorMessage)}).finally(()=>{this.isLoading(!1)})},paginationChanged(t){this.params.page=t.page,this.getData()}}},Q={class:""},W={key:0,class:"text-sm text-gray-700"},Y={class:"border rounded p-2"},Z={class:"w-full overflow-x-auto md:rounded-lg"},ee={ref:"tableRef ",class:"table-auto text-sm text-gray-500"},te={class:"sticky top-0 shadow-md text-xs text-gray-700 bg-gray-50"},se={class:"text-sm text-center"},ae={scope:"col",class:"px-2 py-3 duration-300 hover:text-gray-500 hover:scale-[99%]"},re={class:"flex items-center justify-center"},oe={class:"px-0"},ie={scope:"col",class:"px-2 py-3 duration-300 hover:text-gray-500 hover:scale-[99%]"},ne={class:"flex items-center justify-center"},le={class:"px-2"},de={scope:"col",class:"px-8 py-3 duration-300 hover:text-gray-500 hover:scale-[99%]"},ce={class:"flex items-center justify-center"},he={class:"px-2"},pe={scope:"col",class:"px-2 py-3 duration-300 hover:text-gray-500 hover:scale-[99%]"},_e={class:"flex items-center justify-center"},ue={class:"px-2"},ge={scope:"col",class:"px-2 py-3 duration-300 hover:text-gray-500 hover:scale-[99%]"},ye={class:"flex items-center justify-center"},me={class:"px-2"},be={scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[99%]"},fe={class:"flex items-center justify-center"},xe={class:"px-2"},ve={scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[99%]"},we={class:"flex items-center justify-center"},ke={class:"px-2"},Ce={scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[99%]"},De={class:"flex items-center justify-center"},Ie={class:"px-2"},Me={scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[99%]"},Se={class:"flex items-center justify-center"},$e={class:"px-2"},je={scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[99%]"},Te={class:"flex items-center justify-center"},Pe={class:"px-2"},Le={scope:"col",class:"px-2 py-3"},Ue={class:"overflow-y-scroll text-xs"},Ee={class:"px-2 py-4",style:{"font-family":"Serif!important"}},Be={class:"px-2 py-4"},Fe={class:"px-2 py-4"},Oe={class:"px-2 py-4 text-xs"},Ae={class:"px-2 py-4","data-te-dropdown-ref":""},Ne=["id"],ze=["aria-labelledby"],Ve=["onClick"],qe=e("hr",{class:"border-gray-200"},null,-1),Ge={class:"px-2 py-4"},Xe={class:"inline-flex rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]",role:"group"},Ke=["onClick"],Je=["id"],Re={"data-te-modal-dialog-ref":"",class:"pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out p-5 md:p-10"},He={class:"min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600"},Qe={class:"flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50"},We={class:"text-xl text-primary-500 font-medium leading-normal text-neutral-800 dark:text-neutral-200",id:"exampleModalLabel"},Ye=e("button",{type:"button",class:"box-content text-danger rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none","data-te-modal-dismiss":"","aria-label":"Close"},[e("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"h-6 w-6"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6 18L18 6M6 6l12 12"})])],-1),Ze={class:"relative flex-auto p-4","data-te-modal-body-ref":""},et={class:"px-2 flex flex-col md:px-4"},tt={class:"flex-col bg-white overflow-x-auto rounded-lg"},st={class:"flex flex-wrap items-center justify-between py-1 dark:bg-gray-800 p-4"},at={class:"flex items-center p-1"},rt={class:"relative mx-1","data-te-dropdown-ref":""},ot={type:"button",id:"dropdownPaginate","data-te-dropdown-toggle-ref":"","aria-expanded":"false","data-te-ripple-init":"","data-te-ripple-color":"light",class:"inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"},it=e("span",{class:"sr-only"},"table actions",-1),nt={ref:"paginateMenu","data-te-dropdown-menu-ref":"",class:"min-w-[12rem] absolute z-[1000] start-0 text-gray-500 m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-start text-base shadow-lg dark:bg-neutral-700 [&[data-te-dropdown-show]]:block",tabindex:"-1",role:"menu","aria-orientation":"vertical","aria-label":"User menu","aria-labelledby":"dropdownPaginate"},lt={class:""},dt=["onClick"],ct=e("hr",{class:"border-gray-200 dark:border-gray-700"},null,-1),ht={class:"relative p-1"},pt=e("label",{for:"table-search",class:"sr-only"},"Search",-1),_t={class:"absolute inset-y-0 cursor-pointer text-gray-500 hover:text-gray-700 start-0 flex items-center px-3"},ut=["placeholder"],gt={class:"mb-2"},yt={class:"grow"},mt=["onClick"],bt={class:"w-full text-sm text-start text-gray-500 dark:text-gray-400"},ft={class:"text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400"},xt={class:"text-sm text-center"},vt=["onClick"],wt={class:"flex items-center justify-center"},kt={class:"px-2"},Ct={class:""},Dt={class:"animate-pulse bg-white text-center border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"},It=e("td",{class:"w-4 p-4"},[e("div",{class:"flex items-center"},[e("input",{id:"checkbox-table-search-1",type:"checkbox",class:"w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"})])],-1),Mt=e("td",{class:"flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white"},[e("div",{class:"w-10 h-10 rounded-full"}),e("div",{class:"px-3"},[e("div",{class:"text-base bg-gray-200 px-5 py-2 rounded-lg"}),e("div",{class:"font-normal text-gray-500"})])],-1),St=e("td",{class:"px-2 py-4"},[e("div",{class:"bg-gray-200 px-5 py-2 rounded-lg"})],-1),$t=[It,Mt,St],jt=["onClick"],Tt={key:0},Pt={class:"text-xs"},Lt={key:1,class:"px-2 py-4"},Ut=e("div",{class:"flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50"},null,-1);function Et(t,a,l,Bt,d,c){const D=u("PrimaryButton"),k=u("InputError"),I=u("Link"),M=u("TrashIcon"),S=u("ChevronDownIcon"),$=u("Pagination"),j=u("MagnifyingGlassIcon"),C=u("XMarkIcon"),T=u("UserSelector"),P=u("ArrowsUpDownIcon");return o(),i(p,null,[e("div",Q,[l.label?(o(),i("div",W,r(l.label),1)):f("",!0),e("div",Y,[h(D,{type:"button",onClick:a[0]||(a[0]=s=>{c.getData(),d.Modal.show()})},{default:x(()=>[v(r(t.__("ready_orders")),1)]),_:1}),e("div",Z,[e("table",ee,[e("thead",te,[e("tr",se,[e("th",ae,[e("div",re,[e("span",oe,r(t.__("id")),1)])]),e("th",ie,[e("div",ne,[e("span",le,r(t.__("repository_id")),1)])]),e("th",de,[e("div",ce,[e("span",he,r(t.__("items")),1)])]),e("th",pe,[e("div",_e,[e("span",ue,r(t.__("receiver")),1)])]),e("th",ge,[e("div",ye,[e("span",me,r(t.__("county")),1)])]),e("th",be,[e("div",fe,[e("span",xe,r(`${t.__("city")}/${t.__("district")}`),1)])]),e("th",ve,[e("div",we,[e("span",ke,r(t.__("shipping_price")),1)])]),e("th",Ce,[e("div",De,[e("span",Ie,r(t.__("total_price")),1)])]),e("th",Me,[e("div",Se,[e("span",$e,r(t.__("delivery_time")),1)])]),e("th",je,[e("div",Te,[e("span",Pe,r(t.__("status")),1)])]),e("th",Le,r(t.__("actions")),1)])]),e("tbody",Ue,[(o(!0),i(p,null,g(l.selecteds,(s,_)=>(o(),i("tr",{class:y(["text-center border-b hover:bg-gray-50",_%2==1?"bg-gray-50":"bg-white"])},[e("td",Ee,r(`${s.type=="agency"?"A":""}${t.f2e(s.id)}`),1),e("td",Be,[v(r(s.from_repo_id)+" ",1),h(k,{class:"mt-1",message:l.error.orders},null,8,["message"])]),e("td",Fe,[(o(!0),i(p,null,g(s.items,(n,b)=>(o(),i("div",{class:y(["text-xs",{"border-b":b+1<s.items.length}])},r(`${n.name} ( ${parseFloat(n.qty)} ${t.getPack(n.variation.pack_id)}  ${parseFloat(n.variation.weight)} ${t.__("kg")})`),3))),256))]),e("td",Oe,r(`${s.to_fullname||""}
${s.to_phone||""}`),1),e("td",null,r(t.getCityName(s.to_county_id)),1),e("td",null,r(t.getCityName(s.to_district_id)),1),e("td",null,r(s.total_shipping_price),1),e("td",null,r(t.asPrice(s.total_price)),1),e("td",null,r(`${t.toShamsi(s.delivery_date)}
${s.delivery_timestamp}`),1),e("td",Ae,[e("button",{type:"button",id:`dropdownStatusSetting${s.id}`,"data-te-dropdown-toggle-ref":"","aria-expanded":"false","data-te-ripple-init":"","data-te-ripple-color":"light",class:y(["min-w-[5rem] px-1 cursor-pointer items-center text-center rounded-md py-[.2rem]",`bg-${t.getStatus("order_statuses",s.status).color}-100 hover:bg-${t.getStatus("order_statuses",s.status).color}-200 text-${t.getStatus("order_statuses",s.status).color}-500`])},r(t.getStatus("order_statuses",s.status).name),11,Ne),e("ul",{ref_for:!0,ref:`statusMenu${s.id}`,"data-te-dropdown-menu-ref":"",class:"absolute z-[1000] m-0 hidden list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-center text-base shadow-lg [&[data-te-dropdown-show]]:block",tabindex:"-1",role:"menu","aria-orientation":"vertical","aria-label":"User menu","aria-labelledby":`dropdownStatusSetting${s.id}`},[(o(!0),i(p,null,g(s.statuses,(n,b)=>(o(),i("li",{role:"menuitem",onClick:m=>t.showDialog("danger",n.message,t.__("accept"),c.editOrder,{idx:_,id:s.id,type:s.type,cmnd:"status",status:n.name}),class:"cursor-pointer text-sm transition-colors hover:bg-gray-100"},[e("div",{class:y(["flex items-center justify-center px-6 py-2",` hover:bg-gray-200 text-${n.color}-500`])},r(t.__(n.name)),3),qe],8,Ve))),256))],8,ze)]),f("",!0),e("td",Ge,[e("div",Xe,[h(I,{type:"button",target:"_blank",href:t.route(`admin.panel.order.${s.type}.edit`,s.id),class:"inline-block shadow-sm rounded bg-blue-500 text-white px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-blue-400 focus:outline-none focus:ring-0","data-te-ripple-init":"","data-te-ripple-color":"light"},{default:x(()=>[v(r(t.__("details")),1)]),_:2},1032,["href"]),e("div",{type:"button",onClick:n=>c.removeItem(s,_),class:"rounded shadow-sm cursor-pointer text-white bg-red-500 hover:bg-red-400 text-sm px-6 py-2 mx-1"},[h(M,{class:"w-4 h-4"})],8,Ke)])])],2))),256))])],512)])]),h(k,{class:"mt-1",message:l.error.orders},null,8,["message"])]),e("div",{"data-te-modal-init":"",class:"fixed left-0 top-0 backdrop-blur z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none",id:`modalOrders-${l.id}`,tabindex:"-1","aria-labelledby":"exampleModalLabel","aria-hidden":"true"},[e("div",Re,[e("div",He,[e("div",Qe,[e("h5",We,r(t.__("ready_orders")),1),Ye]),e("div",Ze,[e("div",et,[e("div",tt,[e("div",st,[e("div",at,[e("div",rt,[e("button",ot,[it,e("span",null,r(d.params.paginate),1),h(S,{class:"h-4 w-4 mx-1"})]),e("div",nt,[(o(!0),i(p,null,g(t.$page.props.pageItems,s=>(o(),i("div",lt,[e("div",{onClick:w(_=>{d.params.paginate=s,c.getData()},["stop"]),role:"menuitem",class:"cursor-pointer select-none block p-2 px-6 text-sm transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"},r(s),9,dt),ct]))),256))],512)]),h($,{onPaginationChanged:c.paginationChanged,pagination:d.pagination},null,8,["onPaginationChanged","pagination"])]),e("div",ht,[pt,e("div",_t,[h(j,{onClick:a[1]||(a[1]=s=>c.getData()),class:"w-4 h-4 dark:text-gray-400"})]),e("div",{class:"absolute inset-y-0 end-0 text-gray-500 flex items-center px-3 cursor-pointer hover:text-gray-700",onClick:a[2]||(a[2]=s=>{d.params.search=null,c.getData()})},[h(C,{class:"w-4 h-4 dark:text-gray-400"})]),E(e("input",{type:"text",id:"table-search-users","onUpdate:modelValue":a[3]||(a[3]=s=>d.params.search=s),onKeydown:a[4]||(a[4]=F(w(s=>c.getData(),["prevent"]),["enter"])),class:"block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500",placeholder:t.__("search")},null,40,ut),[[B,d.params.search]])])]),e("div",gt,[h(T,{colsData:["id","name","phone","agency"],labelsData:["id","name","phone","agency"],callback:{agency:s=>`${s.name||""} (${s.id||""})`},link:t.route("admin.panel.repository.search")+"?status=active",label:t.__("repository"),onChange:c.getData,id:"repository",selected:d.params.repo_id,"onUpdate:selected":a[5]||(a[5]=s=>d.params.repo_id=s),preload:null},{selector:x(s=>[e("div",{class:y([(s.selectedText,"py-2"),"px-4 border border-gray-300 rounded hover:bg-gray-100 cursor-pointer flex items-center"])},[e("div",yt,r(s.selectedText??t.__("select")),1),s.selectedText?(o(),i("div",{key:0,class:"bg-danger rounded p-2 cursor-pointer text-white hover:bg-danger-400",onClick:w(_=>{s.clear(),c.getData()},["stop"])},[h(C,{class:"w-5 h-5"})],8,mt)):f("",!0)],2)]),_:1},8,["callback","link","label","onChange","selected"])]),e("table",bt,[e("thead",ft,[e("tr",xt,[(o(!0),i(p,null,g(t.cols,(s,_)=>(o(),i("th",{scope:"col",class:"px-2 py-3 cursor-pointer hover:text-gray-500",onClick:n=>{d.params.order_by=s,d.params.dir=d.params.dir=="ASC"?"DESC":"ASC",d.params.page=1,c.getData()}},[e("div",wt,[e("span",kt,r(t.__(t.labels[_])),1),h(P,{class:"w-4 h-4"})])],8,vt))),256))])]),e("tbody",Ct,[d.loading?(o(),i(p,{key:0},g(3,s=>e("tr",Dt,$t)),64)):f("",!0),(o(!0),i(p,null,g(d.data,(s,_)=>(o(),i("tr",{class:y([{"border-b":_!=d.data.length-1},"cursor-pointer hover:bg-gray-400 bg-white text-center dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"]),onClick:n=>c.selectItem(s,_)},[(o(!0),i(p,null,g(t.cols,(n,b)=>(o(),i(p,null,[b==1?(o(),i("td",Tt,[(o(!0),i(p,null,g(s[n],(m,Ft)=>(o(),i("div",Pt,r(`${m.name} ( ${parseFloat(m.qty)} ${t.getPack(m.variation.pack_id)}  ${parseFloat(m.variation.weight)} ${t.__("kg")})`),1))),256))])):(o(),i("td",Lt,r(l.callback&&l.callback[n]?l.callback[n](s[n]):t.__(s[n])),1))],64))),256))],10,jt))),256))])])])])]),Ut])])],8,Je)],64)}const Rt=X(H,[["render",Et]]);export{Rt as O};
