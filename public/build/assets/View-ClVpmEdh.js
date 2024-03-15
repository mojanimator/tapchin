import{S as m}from"./Scaffold-D_ifbLkl.js";import{_ as u}from"./SecondaryButton-Bn4R_gjB.js";import{_ as h}from"./PrimaryButton-Fk5jf8Nh.js";import{j as f,i as n,o as r,c as x,w as l,e as a,t as s,f as i,a as e,b as c,g}from"./app-DeJr7fNP.js";import{L as y}from"./LoadingIcon-8dx7WYGr.js";import{I as v}from"./Footer-BYUJXMx0.js";import{A as w}from"./Article-CxGv6SoN.js";import{_ as b}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{r as k,a as B,b as V}from"./UserIcon-0EqPaU23.js";import"./Alert-CDDSmLjy.js";import"./ApplicationLogo-CV6pwTdm.js";import"./Pagination-VNHzEPuv.js";import"./ChevronRightIcon-9GRJ1f_S.js";import"./TextEditor-7zAQTOol.js";import"./InputLabel-C2gVT93H.js";import"./Tooltip-CSg56dN2.js";import"./Podcast-BxCdE2Ha.js";import"./SpeakerXMarkIcon-CyRlW18-.js";import"./XMarkIcon-CEnCPALs.js";import"./Video-BLyjCQx2.js";import"./Banner-DAn6f3HS.js";import"./MagnifyingGlassIcon--KPgqpVy.js";import"./ChevronUpIcon-DGI4GkYP.js";import"./PlusIcon-NsBgz0vi.js";import"./QuestionMarkCircleIcon-jeUFMgsk.js";const I={data(){return{html:null,timer:0,timerPercent:0,auto_view:this.$page.props.auto_view,error:null,data:null,available_sites:0,hiddenProp:null,intervalId:null,sites:[],loading:!1,params:{page:0,search:null,order_by:null,dir:null}}},components:{Scaffold:m,Image:v,Link:f,EyeIcon:k,CurrencyDollarIcon:B,LoadingIcon:y,PrimaryButton:h,SecondaryButton:u,UserIcon:V,Article:w},created(){},mounted(){this.data=this.$page.props.data,this.increaseView(this.data.id)},methods:{increaseView(o){window.axios.post(route("article.view"),{id:o})}}},L={key:0},N=["content"],S={class:"flex justify-center pt-24"},C={class:"w-full rounded-lg overflow-x-hidden max-w-4xl xs:mx-2 md:mx-4 blur-xs opacity-75 bg-white backdrop-filter"},P={key:0,class:"text-center flex flex-col font-bold p-4 text-danger text-lg"},j={class:"text-gray-900"},T={key:1,class:"flex flex-col"},D={class:"px-4 py-4 text-white bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-500 to-lime-600"},E={key:0,class:"flex flex-col"},$={class:"grid grid-cols-1 items-center h-64 w-full relative"},A={class:"h-[inherit]"},H=e("div",{class:"text-sm absolute top-0 start-0 w-full h-full bg-primary-500 opacity-10 backdrop-blur"},null,-1),M={key:0,class:"text-sm bg-gray-200 p-4 rounded flex flex-wrap"},U={class:"text-gray-500"},q=e("span",{class:"border-s mx-2"},null,-1),z={class:"text-gray-500"},F={class:"space-y-2 flex flex-col my-2 px-4"},G={class:"text-sm my-2"},J={class:"text-gray-500"},K=e("div",{class:"border-b my-2 border-primary-200"},null,-1),O={key:1,class:"container self-center space-y-2 flex flex-col my-2 px-4"},Q=["innerHTML"];function R(o,W,X,Y,t,Z){const d=n("Link"),p=n("Image"),ee=n("PrimaryButton"),_=n("Scaffold");return r(),x(_,{"navbar-theme":"light"},{header:l(()=>[t.data?(r(),a("title",L,s(t.data.title),1)):i("",!0),t.data?(r(),a("meta",{key:1,name:"description",content:t.data.summary},null,8,N)):i("",!0)]),default:l(()=>[e("section",S,[e("div",C,[o.$page.props.error_message?(r(),a("div",P,[e("div",j,s(o.$page.props.error_message),1),c(d,{href:o.$page.props.error_link,class:"my-4"},{default:l(()=>[g(s(o.__("return")),1)]),_:1},8,["href"])])):t.data?(r(),a("div",T,[e("div",D,s(t.data.title),1),t.data?(r(),a("div",E,[e("div",$,[e("div",A,[c(p,{src:o.route("storage.articles")+`/${t.data.id}.jpg`,classes:"object-cover    h-[inherit]     w-full"},null,8,["src"]),H])]),t.data.owner?(r(),a("p",M,[e("span",U,s(o.__("author"))+": ",1),e("span",null,s(t.data.author),1),q,e("span",z,s(o.__("phone"))+": ",1),e("span",null,s(t.data.owner.phone),1)])):i("",!0),e("div",F,[e("p",G,[e("span",J,s(o.__("tags"))+": ",1),e("span",null,s(t.data.tags),1)])]),K,t.data.content?(r(),a("div",O,[e("div",{innerHTML:t.data.content},null,8,Q)])):i("",!0)])):i("",!0)])):i("",!0)])]),i("",!0)]),_:1})}const Le=b(I,[["render",R]]);export{Le as default};
