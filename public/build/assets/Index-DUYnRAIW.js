import{L as B}from"./LoadingIcon-8dx7WYGr.js";import{r as P,T as V}from"./TomanIcon-B4mk9q1h.js";import{I as j}from"./Footer-BYUJXMx0.js";import{S as A}from"./Scaffold-D_ifbLkl.js";import{Z as D,j as N,i as n,o as i,c as m,w as p,a as o,t as a,b as l,e as c,h as x,F as y,f as u,k as U,v as F,n as h,l as M,g as E}from"./app-DeJr7fNP.js";import{h as H}from"./hero-CnHX6oGc.js";import{_ as $}from"./PrimaryButton-Fk5jf8Nh.js";import{_ as O}from"./SecondaryButton-Bn4R_gjB.js";import{_ as Y}from"./SearchInput-CiaqlZI_.js";import{L as Z}from"./LocationSelector-D1EPx4sD.js";import{S as q,a as G}from"./swiper-vue-CS5c3IvU.js";import{N as J,P as K,A as Q}from"./scrollbar-D-KXdfFN.js";import{S as R}from"./scrollbar-T_jY5SJb.js";import{C as W}from"./CartItemButton-BUsVs_zz.js";import{_ as X}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{r as ee}from"./EyeIcon-DI71KuzS.js";import{r as te}from"./PencilIcon-S7Aam_v7.js";import{r as oe}from"./MapPinIcon-DUvwgUpM.js";import"./Alert-CDDSmLjy.js";import"./ApplicationLogo-CV6pwTdm.js";import"./InputLabel-C2gVT93H.js";import"./TextInput-BUWn7uYR.js";import"./Selector-NdNaM2dQ.js";import"./Bars2Icon-DNQAv7Yb.js";import"./ChevronRightIcon-9GRJ1f_S.js";import"./ChevronUpIcon-DGI4GkYP.js";import"./PlusIcon-NsBgz0vi.js";import"./MinusIcon-B-7x8Y4K.js";const se={data(){return{products:[],categories:[],heroImage:H,loading:!1,total:0,params:{page:0,search:null,products:[],order_by:null,dir:null,parent_ids:[],province_id:this.getUserProvinceId(),city_id:this.getUserCityId()},modules:[J,K,R,Q]}},props:["heroText"],components:{CartItemButton:W,SearchInput:Y,SecondaryButton:O,PrimaryButton:$,Scaffold:A,Head:D,LoadingIcon:B,Image:j,EyeIcon:ee,Link:N,PencilIcon:te,Swiper:q,SwiperSlide:G,LocationSelector:Z,MapPinIcon:oe,ArrowTrendingUpIcon:P,TomanIcon:V},setup(t){},mounted(){this.setScroll(this.$refs.loader)},methods:{getData(t){t==0&&(this.params.page=1,this.products=[]),!(this.total>0&&this.total<=this.products.length)&&(this.loading=!0,window.axios.get(route("variation.search"),{params:this.params}).then(s=>{this.total=s.data.total,this.params.page=s.data.current_page+1,this.products=this.products.concat(s.data.data)}).catch(s=>{this.error=this.getErrors(s),this.showToast("danger",this.error)}).finally(()=>{this.loading=!1}))},setScroll(t){window.onscroll=()=>{let s=t.offsetTop,f=t.offsetTop+t.offsetHeight,g=window.pageYOffset+window.innerHeight,r=window.pageYOffset;g+300>s&&r<f+200&&!this.loading&&this.getData()}}}},re=o("div",{class:"py-8 shadow-md bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-teal-500 to-teal-500"},null,-1),ie={class:"flex flex-wrap gap-2 w-full bg-gray-100 rounded-b-2xl shadow-md p-2 px-2 lg:px-4 items-center z-[-10]"},ae={key:0,class:"container-lg mx-auto"},ne={class:"mt-6 gap-y-3 gap-x-2 grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"},le={class:"bg-white shadow-md rounded-lg"},ce=["id","onClick"],de={class:"hidden sm:flex min-w-[36%] mx-auto"},me={class:"p-4 w-full flex flex-col items-stretch justify-start items-start items-between"},pe={class:"flex items-center justify-between"},ue={class:"text-primary-600 ms-1"},_e={class:"text-sm text-neutral-500 mx-2"},he=o("hr",{class:"border-gray-200 m-2"},null,-1),fe={class:"text-neutral-500 text-sm"},ge={class:"flex items-center text-sm"},ve={key:0,class:"text-sm text-neutral-500 mx-2"},we={class:"flex items-center text-sm"},xe={class:"text-sm text-neutral-500 mx-2"},ye={class:"flex items-center justify-end"},be={key:0,xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 14 14",class:"fill-gray-500 h-5 w-5"},Ie=o("path",{"fill-rule":"evenodd",d:"M3.057 1.742L3.821 1l.78.75-.776.741-.768-.749zm3.23 2.48c0 .622-.16 1.111-.478 1.467-.201.221-.462.39-.783.505a3.251 3.251 0 01-1.083.163h-.555c-.421 0-.801-.074-1.139-.223a2.045 2.045 0 01-.9-.738A2.238 2.238 0 011 4.148c0-.059.001-.117.004-.176.03-.55.204-1.158.525-1.827l1.095.484c-.257.532-.397 1-.419 1.403-.002.04-.004.08-.004.12 0 .252.055.458.166.618a.887.887 0 00.5.354c.085.028.178.048.278.06.079.01.16.014.243.014h.555c.458 0 .769-.081.933-.244.14-.139.21-.383.21-.731V2.02h1.2v2.202zm5.433 3.184l-.72-.7.709-.706.735.707-.724.7zm-2.856.308c.542 0 .973.19 1.293.569.297.346.445.777.445 1.293v.364h.18v-.004h.41c.221 0 .377-.028.467-.084.093-.055.14-.14.14-.258v-.069c.004-.243.017-1.044 0-1.115L13 8.05v1.574a1.4 1.4 0 01-.287.863c-.306.405-.804.607-1.495.607h-.627c-.061.733-.434 1.257-1.117 1.573-.267.122-.58.21-.937.265a5.845 5.845 0 01-.914.067v-1.159c.612 0 1.072-.082 1.38-.247.25-.132.376-.298.376-.499h-.515c-.436 0-.807-.113-1.113-.339-.367-.273-.55-.667-.55-1.18 0-.488.122-.901.367-1.24.296-.415.728-.622 1.296-.622zm.533 2.226v-.364c0-.217-.048-.389-.143-.516a.464.464 0 00-.39-.187.478.478 0 00-.396.187.705.705 0 00-.136.449.65.65 0 00.003.067c.008.125.066.22.177.283.093.054.21.08.352.08h.533zM9.5 6.707l.72.7.724-.7L10.209 6l-.709.707zm-6.694 4.888h.03c.433-.01.745-.106.937-.29.024.012.065.035.12.068l.074.039.081.042c.135.073.261.133.379.18.345.146.67.22.977.22a1.216 1.216 0 00.87-.34c.3-.285.449-.714.449-1.286a2.19 2.19 0 00-.335-1.145c-.299-.457-.732-.685-1.3-.685-.502 0-.916.192-1.242.575-.113.132-.21.284-.294.456-.032.062-.06.125-.084.191a.504.504 0 00-.03.078 1.67 1.67 0 00-.022.06c-.103.309-.171.485-.205.53-.072.09-.214.14-.427.147-.123-.005-.209-.03-.256-.076-.057-.054-.085-.153-.085-.297V7l-1.201-.5v3.562c0 .261.048.496.143.703.071.158.168.296.29.413.123.118.266.211.43.28.198.084.42.13.665.136v.001h.036zm2.752-1.014a.778.778 0 00.044-.353.868.868 0 00-.165-.47c-.1-.134-.217-.201-.35-.201-.18 0-.33.103-.447.31-.042.071-.08.158-.114.262a2.434 2.434 0 00-.04.12l-.015.053-.015.046c.142.118.323.216.544.293.18.062.325.092.433.092.044 0 .086-.05.125-.152z","clip-rule":"evenodd"},null,-1),ke=[Ie],Se={key:0,class:"flex items-center"},Ce={class:"flex sm:hidden min-w-[100%] xs:min-w-[70%] me-auto"},Le={key:1,class:"font-bold text-rose-500 mt-8 justify-center flex flex-col items-center"},Te={ref:"loader"};function ze(t,s,f,g,r,_){const b=n("LocationSelector"),I=n("SearchInput"),v=n("Image"),k=n("swiper-slide"),S=n("swiper"),w=n("CartItemButton"),C=n("ArrowTrendingUpIcon"),L=n("TomanIcon"),T=n("LoadingIcon"),z=n("Scaffold");return i(),m(z,{"navbar-theme":"dark"},{header:p(()=>[o("title",null,a(t.__("shop")),1)]),default:p(()=>[re,o("section",ie,[l(b,{onChange:s[0]||(s[0]=e=>{r.params.province_id=e.province_id,r.params.county_id=e.county_id,r.params.district_id=e.district_id,_.getData(0)})}),l(I,{modelValue:r.params.search,"onUpdate:modelValue":s[1]||(s[1]=e=>r.params.search=e),onSearch:s[2]||(s[2]=e=>_.getData(0))},null,8,["modelValue"]),l(S,{modules:[r.modules[0],r.modules[2],r.modules[3]],"slides-per-view":"auto","space-between":16,pagination:{clickable:!0},scrollbar:{draggable:!0},onSwiper:s[3]||(s[3]=()=>{}),onSlideChange:s[4]||(s[4]=()=>{}),class:"w-full p-3"},{default:p(()=>[(i(!0),c(y,null,x(t.$page.props.products,e=>(i(),m(k,{onClick:d=>{t.toggleArray(e.id,r.params.parent_ids),_.getData(0)},class:h([{"bg-primary-500 hover:bg-primary-400":r.params.parent_ids.filter(d=>d==e.id).length>0},"flex max-w-[3.5rem] flex-col rounded p-1 mb-4 items-center hover:cursor-pointer hover:bg-gray-50 hover:scale-[105%]"])},{default:p(()=>[o("div",null,[l(v,{classes:"rounded-full h-12 w-12 border-primary-500 border",src:t.route("storage.products")+`/${e.id}.jpg`},null,8,["src"])]),o("div",{class:h([{"text-white":r.params.parent_ids.filter(d=>d==e.id).length>0},"text-xs text-center text-neutral-500"])},a(t.replaceAll(e.name," ","‌")),3)]),_:2},1032,["onClick","class"]))),256))]),_:1},8,["modules"])]),r.products.length>0?(i(),c("section",ae,[o("div",ne,[(i(!0),c(y,null,x(r.products,(e,d)=>(i(),c("div",le,[o("article",{id:e.id,onClick:M(Be=>t.$inertia.visit(t.route("variation.view",{id:e.id,name:e.name})),["self"]),class:"overflow-hidden flex flex-row sm:flex-col hover:bg-gray-100 hover:cursor-pointer hover:scale-[101%] duration-300"},[l(v,{classes:"object-contain md:mx-auto sm:h-64      w-32 sm:w-full  h-32  rounded-b-lg mx-2",src:t.route("storage.variations")+`/${e.id}/thumb.jpg`},null,8,["src"]),o("div",de,[(i(),m(w,{key:e.id,class:"w-full","product-id":e.id},null,8,["product-id"]))]),o("div",me,[o("div",pe,[o("div",ue,a(e.name),1),o("div",_e,a(t.__("grade")+" "+e.grade),1)]),he,o("div",fe,a(e.repo_name),1),o("div",ge,[o("div",null,a(t.__("in_stock")+` : ${parseFloat(e.in_shop)}`),1),t.getPack(e.pack_id)?(i(),c("div",ve,a(` ${t.getPack(e.pack_id)} `),1)):u("",!0)]),o("div",we,[o("div",null,a(t.__("weight")+` : ${parseFloat(e.weight)}`),1),o("div",xe,a(t.__("kg")),1)]),o("div",ye,[o("div",{class:h(["flex items-center",{"line-through text-neutral-500":t.$page.props.is_auction&&e.in_auction}])},[E(a(t.asPrice(e.price))+" ",1),t.$page.props.is_auction&&e.in_auction?(i(),c("svg",be,ke)):u("",!0)],2),e.in_auction==!0?(i(),c("div",Se,[l(C,{class:"rotate-180 text-neutral-500 mx-2"}),o("span",null,a(t.asPrice(e.auction_price)),1)])):u("",!0),l(L,{class:"w-4 h-4 mx-2"})]),o("div",Ce,[(i(),m(w,{key:e.id,class:"w-full","product-id":e.id},null,8,["product-id"]))])])],8,ce)]))),256))])])):r.loading?u("",!0):(i(),c("section",Le,[o("div",null,a(t.__("no_product_in_selected_city")),1)])),o("div",Te,[U(l(T,{type:"linear"},null,512),[[F,r.loading]])],512)]),_:1})}const it=X(se,[["render",ze]]);export{it as default};
