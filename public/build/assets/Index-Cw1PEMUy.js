import"./Scaffold-D_ifbLkl.js";import{P as E}from"./Panel-rs2tkraC.js";import{Z as M,j as U,i as p,o as d,c as L,w as u,a as e,t as i,b as n,g as b,e as c,h as y,F as w,k as v,G as V,J as B,A as C,f as h,n as z}from"./app-DeJr7fNP.js";import{P as G,r as N}from"./Pagination-VNHzEPuv.js";import{r as q,I as X}from"./Footer-BYUJXMx0.js";import{T as F}from"./Tooltip-CSg56dN2.js";import{_ as H}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{a as K}from"./ShoppingBagIcon-zIu7FSuq.js";import{r as J}from"./Bars2Icon-DNQAv7Yb.js";import{r as W}from"./MagnifyingGlassIcon--KPgqpVy.js";import{r as Z}from"./CartItemButton-BUsVs_zz.js";import"./Alert-CDDSmLjy.js";import"./LoadingIcon-8dx7WYGr.js";import"./ApplicationLogo-CV6pwTdm.js";import"./TextInput-BUWn7uYR.js";import"./InputLabel-C2gVT93H.js";import"./WrenchScrewdriverIcon-DQFoZ8JW.js";import"./QuestionMarkCircleIcon-jeUFMgsk.js";import"./ChevronRightIcon-9GRJ1f_S.js";import"./ChevronUpIcon-DGI4GkYP.js";import"./PlusIcon-NsBgz0vi.js";import"./MinusIcon-B-7x8Y4K.js";const O={data(){return{params:{page:1,search:null,paginate:this.$page.props.pageItems[0],order_by:null,dir:"DESC"},data:[],pagination:{},toggleSelect:!1,loading:!1,error:null}},components:{Head:M,Link:U,HomeIcon:K,ChevronDownIcon:q,Panel:E,Bars2Icon:J,Image:X,MagnifyingGlassIcon:W,XMarkIcon:Z,Pagination:G,ArrowsUpDownIcon:N,Tooltip:F},mounted(){this.getData()},methods:{getData(){this.loading=!0,this.data=[],window.axios.get(route("panel.admin.article.search"),{params:this.params},{}).then(t=>{this.data=t.data.data,this.data.forEach(s=>{s.selected=!1}),delete t.data.data,this.pagination=t.data}).catch(t=>{t.response?(console.log(t.response.data),console.log(t.response.status),console.log(t.response.headers),this.error=t.response.data):t.request?(console.log(t.request),this.error=t.request):(console.log("Error",t.message),this.error=t.message),console.log(t.config),this.showToast("danger",t)}).finally(()=>{this.loading=!1})},toggleAll(){this.toggleSelect=!this.toggleSelect,this.data.forEach(t=>{t.selected=this.toggleSelect})},edit(t){this.isLoading(!0),window.axios.patch(route("panel.admin.article.update"),t,{}).then(s=>{s.data&&s.data.message&&this.showToast("success",s.data.message),s.data.charge&&(this.data[t.idx].charge=s.data.charge,this.user.wallet=s.data.wallet),s.data.status&&(this.data[t.idx].status=s.data.status),s.data.view_fee&&(this.data[t.idx].view_fee=s.data.view_fee),s.data.meta&&(this.data[t.idx].meta=s.data.meta,this.user.meta_wallet=s.data.meta_wallet)}).catch(s=>{this.error=this.getErrors(s),s.response&&s.response.data&&(s.response.data.charge&&(this.data[t.idx].charge=s.response.data.charge),s.response.data.view_fee&&(this.data[t.idx].view_fee=s.response.data.view_fee),s.response.data.meta&&(this.data[t.idx].meta=s.response.data.meta)),this.showToast("danger",this.error)}).finally(()=>{this.isLoading(!1)})},paginationChanged(t){this.params.page=t.page,this.getData()},bulkAction(t){if(this.data.filter(l=>l.selected).length==0){this.showToast("danger",this.__("nothing_selected"));return}this.isLoading(!0);const s={cmnd:t,data:this.data.reduce((l,g)=>(g.selected&&l.push(g.id),l),[])};window.axios.patch(route("panel.admin.article.update"),s,{}).then(l=>{if(l.data&&l.data.message&&this.showToast("success",l.data.message),l.data&&l.data.results){const g=l.data.results;for(let o in this.data)for(let r in g)if(g[r].id==this.data[o].id){this.data[o].status=g[r].status;break}}}).catch(l=>{this.error=this.getErrors(l),this.showToast("danger",this.error)}).finally(()=>{this.isLoading(!1)})}}},Q={class:"flex items-center justify-between px-4 py-2 text-primary-500 border-b md:py-4"},R={class:"flex"},Y={class:"text-2xl font-semibold"},$={class:"px-2 flex flex-col md:px-4"},ee={class:"flex-col bg-white overflow-x-auto shadow-lg rounded-lg"},te={class:"flex items-center justify-between py-4 p-4"},se={class:"relative mx-1","data-te-dropdown-ref":""},ae={id:"dropdownActionsSetting","data-te-dropdown-toggle-ref":"","aria-expanded":"false","data-te-ripple-init":"","data-te-ripple-color":"light",class:"inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5"},oe=e("span",{class:"sr-only"},"table actions",-1),ie={ref:"actionsMenu","data-te-dropdown-menu-ref":"",class:"min-w-[12rem] absolute z-[1000] float-start m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-start text-base shadow-lg [&[data-te-dropdown-show]]:block",tabindex:"-1",role:"menu","aria-orientation":"vertical","aria-label":"Actions menu","aria-labelledby":"dropdownActionsSetting"},re={class:"flex items-center"},ne={class:"relative mx-1","data-te-dropdown-ref":""},le={id:"dropdownPaginate","data-te-dropdown-toggle-ref":"","aria-expanded":"false","data-te-ripple-init":"","data-te-ripple-color":"light",class:"inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5"},de=e("span",{class:"sr-only"},"table actions",-1),ce={ref:"userMenu","data-te-dropdown-menu-ref":"",class:"min-w-[12rem] absolute z-[1000] start-0 text-gray-500 m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-start text-base shadow-lg [&[data-te-dropdown-show]]:block",tabindex:"-1",role:"menu","aria-orientation":"vertical","aria-label":"User menu","aria-labelledby":"dropdownPaginate"},pe={class:""},ge=["onClick"],he=e("hr",{class:"border-gray-200"},null,-1),ue={class:"relative"},_e=e("label",{for:"table-search",class:"sr-only"},"Search",-1),me={class:"absolute inset-y-0 cursor-pointer text-gray-500 hover:text-gray-700 start-0 flex items-center px-3"},fe=["placeholder"],xe={class:"w-full text-sm text-left text-gray-500"},be={class:"text-xs text-gray-700 uppercase bg-gray-50"},ye={class:"text-sm text-center"},we={class:"flex items-center"},ve=e("label",{for:"checkbox-all-search",class:"sr-only"},"checkbox",-1),ke={class:"flex items-center justify-center"},Ce={class:"px-2"},Se={class:"flex items-center justify-center"},De={class:"px-2"},Ie={class:"flex items-center justify-center"},Ae={class:"px-2"},je={class:"flex items-center justify-center"},Te={class:"px-2"},Pe={scope:"col",class:"px-2 py-3"},Ee={class:""},Me={class:"animate-pulse bg-white text-center border-b hover:bg-gray-50"},Ue=e("td",{class:"w-4 p-4"},[e("div",{class:"flex items-center"},[e("input",{id:"checkbox-table-search-1",type:"checkbox",class:"w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"})])],-1),Le=e("td",{class:"flex items-center px-6 py-4 text-gray-900 whitespace-nowrap"},[e("div",{class:"w-10 h-10 rounded-full"}),e("div",{class:"px-3"},[e("div",{class:"text-base bg-gray-200 px-5 py-2 rounded-lg"}),e("div",{class:"font-normal text-gray-500"})])],-1),Ve=e("td",{class:"px-2 py-4"},[e("div",{class:"bg-gray-200 px-5 py-2 rounded-lg"})],-1),Be=e("td",{class:"px-2 py-4"},[e("div",{class:"bg-gray-200 px-5 py-2 rounded-lg"})],-1),ze=e("td",{class:"px-2 py-4"},[e("div",{class:"bg-gray-200 px-5 py-2 rounded-lg"})],-1),Ge=e("td",{class:"px-2 py-4"},[e("div",{class:"justify-center bg-gray-200 px-5 py-3 rounded-lg items-center text-center rounded-md"})],-1),Ne=e("td",{class:"px-2 py-4"},[e("div",{class:"bg-gray-200 px-5 py-2 rounded-lg"})],-1),qe=e("td",{class:"px-2 py-4"},[e("div",{class:"bg-gray-200 px-5 py-4 rounded-lg rounded-md",role:"group"})],-1),Xe=[Ue,Le,Ve,Be,ze,Ge,Ne,qe],Fe={class:"bg-white text-center border-b hover:bg-gray-50"},He=["onClick"],Ke={class:"flex items-center"},Je=["onUpdate:modelValue"],We={class:"flex items-center px-6 py-4 text-gray-900 whitespace-nowrap"},Ze={class:"text-base font-semibold"},Oe={class:"font-normal text-gray-500"},Qe={class:"px-2 py-4"},Re={class:"px-2 py-4","data-te-dropdown-ref":""},Ye=["onClick"],$e={class:"flex items-center text-danger px-6 py-2 justify-between"},et=e("span",{class:"bg-danger mx-1 animate-pulse px-1 py-1 rounded"},null,-1),tt=e("hr",{class:"border-gray-200"},null,-1),st={key:1,role:"menuitem",class:"cursor-pointer text-sm text-gray-700 transition-colors hover:bg-gray-100"},at={class:"flex items-center px-6 py-2 justify-between"},ot={key:0},it={key:1},rt=e("hr",{class:"border-gray-200"},null,-1),nt=["onClick"],lt={class:"flex items-center px-6 py-2 justify-between"},dt=e("hr",{class:"border-gray-200"},null,-1),ct={class:"px-2 py-4"},pt={class:"inline-flex rounded-md shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]",role:"group"};function gt(t,s,l,g,o,r){const S=p("Bars2Icon"),f=p("Link"),k=p("ChevronDownIcon"),D=p("Pagination"),I=p("MagnifyingGlassIcon"),A=p("XMarkIcon"),_=p("ArrowsUpDownIcon"),j=p("Tooltip"),T=p("Image"),P=p("Panel");return d(),L(P,null,{header:u(()=>[e("title",null,i(t.__("panel")),1)]),content:u(()=>[e("div",Q,[e("div",R,[n(S,{class:"h-7 w-7 mx-3"}),e("h1",Y,i(t.__("articles_list")),1)]),e("div",null,[n(f,{href:t.route("panel.admin.article.create"),class:"inline-flex items-center justify-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold transition-all duration-500 text-white hover:bg-green-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"},{default:u(()=>[b(i(t.__("new_article")),1)]),_:1},8,["href"])])]),e("div",$,[e("div",ee,[e("div",te,[e("div",null,[e("div",se,[e("button",ae,[oe,e("span",null,i(t.__("bulk_actions")),1),n(k,{class:"h-4 w-4 mx-1"})]),e("div",ie,null,512)])]),e("div",re,[e("div",ne,[e("button",le,[de,e("span",null,i(o.params.paginate),1),n(k,{class:"h-4 w-4 mx-1"})]),e("div",ce,[(d(!0),c(w,null,y(t.$page.props.pageItems,a=>(d(),c("div",pe,[e("div",{onClick:x=>{o.params.paginate=a,r.getData()},role:"menuitem",class:"cursor-pointer select-none block p-2 px-6 text-sm transition-colors hover:bg-gray-100"},i(a),9,ge),he]))),256))],512)]),n(D,{onPaginationChanged:r.paginationChanged,pagination:o.pagination},null,8,["onPaginationChanged","pagination"])]),e("div",ue,[_e,e("div",me,[n(I,{onClick:s[0]||(s[0]=a=>r.getData()),class:"w-4 h-4"})]),e("div",{class:"absolute inset-y-0 end-0 text-gray-500 flex items-center px-3 cursor-pointer hover:text-gray-700",onClick:s[1]||(s[1]=a=>{o.params.search=null,r.getData()})},[n(A,{class:"w-4 h-4"})]),v(e("input",{type:"text",id:"table-search-users","onUpdate:modelValue":s[2]||(s[2]=a=>o.params.search=a),onKeydown:s[3]||(s[3]=B(a=>r.getData(),["enter"])),class:"block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500",placeholder:t.__("search")},null,40,fe),[[V,o.params.search]])])]),e("table",xe,[e("thead",be,[e("tr",ye,[e("th",{scope:"col",class:"p-4",onClick:s[5]||(s[5]=(...a)=>r.toggleAll&&r.toggleAll(...a))},[e("div",we,[v(e("input",{id:"checkbox-all-search",type:"checkbox","onUpdate:modelValue":s[4]||(s[4]=a=>o.toggleSelect=a),class:"w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"},null,512),[[C,o.toggleSelect]]),ve])]),e("th",{scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]",onClick:s[6]||(s[6]=a=>{o.params.order_by="title",o.params.dir=o.params.dir=="ASC"?"DESC":"ASC",o.params.page=1,r.getData()})},[e("div",ke,[e("span",Ce,i(t.__("title")),1),n(_,{class:"w-4 h-4"})])]),e("th",{scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]",onClick:s[7]||(s[7]=a=>{o.params.order_by="view",o.params.dir=o.params.dir=="ASC"?"DESC":"ASC",o.params.page=1,r.getData()})},[e("div",Se,[e("span",De,i(t.__("view")),1),n(_,{class:"w-4 h-4"})])]),t.hasWallet()?(d(),c("th",{key:0,scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]",onClick:s[8]||(s[8]=a=>{o.params.order_by="view_fee",o.params.dir=o.params.dir=="ASC"?"DESC":"ASC",o.params.page=1,r.getData()})},[e("div",Ie,[n(j,{class:"p-2",content:t.__("help_view_fee")},{default:u(()=>[e("span",Ae,i(t.__("view_fee")),1)]),_:1},8,["content"]),n(_,{class:"w-4 h-4"})])])):h("",!0),e("th",{scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]",onClick:s[9]||(s[9]=a=>{o.params.order_by="status",o.params.dir=o.params.dir=="ASC"?"DESC":"ASC",o.params.page=1,r.getData()})},[e("div",je,[e("span",Te,i(t.__("status")),1),n(_,{class:"w-4 h-4"})])]),e("th",Pe,i(t.__("actions")),1)])]),e("tbody",Ee,[o.loading?(d(),c(w,{key:0},y(3,a=>e("tr",Me,Xe)),64)):h("",!0),(d(!0),c(w,null,y(o.data,(a,x)=>(d(),c("tr",Fe,[e("td",{class:"w-4 p-4",onClick:m=>a.selected=!a.selected},[e("div",Ke,[v(e("input",{id:"checkbox-table-search-1",type:"checkbox","onUpdate:modelValue":m=>a.selected=m,class:"w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"},null,8,Je),[[C,a.selected]])])],8,He),e("td",We,[n(T,{class:"w-10 h-10 cursor-pointer rounded-full",src:`${t.route("storage.articles")}/${a.id}.jpg`,"data-lity":`${t.route("storage.articles")}/${a.id}.jpg`,alt:t.cropText(a.title,5)},null,8,["src","data-lity","alt"]),n(f,{class:"px-3 hover:text-gray-500",href:t.route("panel.admin.article.edit",a.id)},{default:u(()=>[e("div",Ze,i(t.cropText(a.title,30)),1),e("div",Oe,i(),1)]),_:2},1032,["href"])]),e("td",Qe,i(a.view),1),e("td",Re,[e("button",{id:"dropdownStatusSetting","data-te-dropdown-toggle-ref":"","aria-expanded":"false","data-te-ripple-init":"","data-te-ripple-color":"light",class:z(["min-w-[5rem] px-1 cursor-pointer items-center text-center rounded-md py-[.2rem]",`bg-${t.getStatus("article",a.status).color}-100 hover:bg-${t.getStatus("article",a.status).color}-200 text-${t.getStatus("article",a.status).color}-500`])},i(t.getStatus("article",a.status).name),3),e("ul",{ref_for:!0,ref:"statusMenu","data-te-dropdown-menu-ref":"",class:"absolute z-[1000] m-0 hidden list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-center text-base shadow-lg [&[data-te-dropdown-show]]:block",tabindex:"-1",role:"menu","aria-orientation":"vertical","aria-label":"User menu","aria-labelledby":"dropdownStatusSetting"},[a.status=="active"?(d(),c("li",{key:0,role:"menuitem",onClick:m=>r.edit({idx:x,id:a.id,cmnd:"inactive"}),class:"cursor-pointer text-sm transition-colors hover:bg-gray-100"},[e("div",$e,[et,b(" "+i(t.__("inactive")),1)]),tt],8,Ye)):h("",!0),a.status=="review"||a.status=="block"?(d(),c("li",st,[e("div",at,[a.status=="review"?(d(),c("span",ot,i(t.__("active_after_review")),1)):h("",!0),a.status=="block"?(d(),c("span",it,i(t.__("not_available")),1)):h("",!0)]),rt])):h("",!0),a.status=="inactive"?(d(),c("li",{key:2,role:"menuitem",onClick:m=>r.edit({idx:x,id:a.id,cmnd:"activate"}),class:"cursor-pointer text-sm text-primary-700 transition-colors hover:bg-gray-100"},[e("div",lt,i(t.__("activate")),1),dt],8,nt)):h("",!0)],512)]),e("td",ct,[e("div",pt,[n(f,{type:"button",href:t.route("panel.admin.article.edit",a.id),class:"inline-block rounded bg-orange-500 text-white px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-orange-400 focus:outline-none focus:ring-0","data-te-ripple-init":"","data-te-ripple-color":"light"},{default:u(()=>[b(i(t.__("edit")),1)]),_:2},1032,["href"])])])]))),256))])])])])]),_:1})}const Lt=H(O,[["render",gt]]);export{Lt as default};
