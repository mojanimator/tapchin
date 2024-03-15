import{D as F,i as d,o as s,e as n,a as o,b as a,w as L,F as m,h as f,t as g,k as S,G as A,J as B,f as u,z as V,c as U,l as N}from"./app-DeJr7fNP.js";import{r as z,P as G}from"./Pagination-VNHzEPuv.js";import{r as H,I as q}from"./Footer-BYUJXMx0.js";import{T as Q}from"./TextEditor-7zAQTOol.js";import{T as X}from"./Tooltip-CSg56dN2.js";import{L as J}from"./LoadingIcon-8dx7WYGr.js";import{_ as K,a as O}from"./InputLabel-C2gVT93H.js";import{P as R}from"./Podcast-BxCdE2Ha.js";import{V as W}from"./Video-BLyjCQx2.js";import{B as Y}from"./Banner-DAn6f3HS.js";import{_ as Z}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{r as ee,a as te}from"./XMarkIcon-CEnCPALs.js";import{r as oe}from"./MagnifyingGlassIcon--KPgqpVy.js";import{r as re}from"./ChevronUpIcon-DGI4GkYP.js";import{r as se}from"./PlusIcon-NsBgz0vi.js";import{r as ne}from"./QuestionMarkCircleIcon-jeUFMgsk.js";const ae={name:"ArticleCell",props:["mode","ownerId","placeholder","error"],components:{Banner:Y,Video:W,Podcast:R,TextEditor:Q,ArrowsUpDownIcon:z,XMarkIcon:ee,MagnifyingGlassIcon:oe,ChevronDownIcon:H,ChevronUpIcon:re,Pagination:G,Image:q,CheckIcon:te,LoadingIcon:J,Tooltip:X,PlusIcon:se,InputLabel:K,InputError:O,QuestionMarkCircleIcon:ne},data(){return{cells:[{key:Date.now(),type:null,id:null,value:null}],params:{page:1,search:null,paginate:this.$page.props.pageItems[0],order_by:null,dir:"DESC",owner_id:this.ownerId||this.$page.props.auth.user?this.$page.props.auth.user.id:null},Modal:null,data:[],pagination:{},loading:!1,removing:!1,uploading:!1,selectingForIndex:null}},mounted(){if(this.mode=="view")return;const e=document.getElementById("modalFiles");this.Modal=new F(e),this.getData()},methods:{move(e,r){let p;e=="up"&&r>0?(p=this.cells[r-1],this.cells[r-1]=this.cells[r],this.cells[r]=p):e=="down"&&r<this.cells.length-1&&(p=this.cells[r+1],this.cells[r+1]=this.cells[r],this.cells[r]=p)},clear(e){this.cells.splice(e,1),this.cells.length==0&&this.cells.push({key:Date.now(),type:null,id:null,value:null})},selectItem(e){this.cells[this.selectingForIndex].type=e.type,this.cells[this.selectingForIndex].value=e.name,this.cells[this.selectingForIndex].id=e.id,this.selectingForIndex=null,this.Modal.hide()},showFilesModal(e){this.selectingForIndex=e,this.Modal.show()},getData(){this.loading=!0,this.data=[],window.axios.get(route("panel.merged.search"),{params:this.params},{}).then(e=>{this.data=e.data.data,delete e.data.data,this.pagination=e.data}).catch(e=>{e.response?(console.log(e.response.data),console.log(e.response.status),console.log(e.response.headers),this.error=e.response.data):e.request?(console.log(e.request),this.error=e.request):(console.log("Error",e.message),this.error=e.message),console.log(e.config),this.showToast("danger",e)}).finally(()=>{this.loading=!1})},paginationChanged(e){this.params.page=e.page,this.getData()},getContent(){let e=[];for(let r in this.cells)e.push({id:this.cells[r].id,type:this.cells[r].type,value:this.cells[r].value}),e[r].type=="content"&&(e[r].value=this.$refs[`text-${e[r].id}`][0].getData());return e},setContent(e){if(!e||e==null)return null;e=JSON.parse(e),this.cells=[];for(let r in e)this.cells.push({key:Date.now()+r,id:e[r].id,type:e[r].type,value:e[r].value}),this.$nextTick(()=>{e[r].type=="content"})}}},le={key:0,class:"rounded border border-dashed p-2"},ie={class:"flex items-center"},de={class:"flex"},ce={class:"grow"},ue={key:0,class:"h-24 border border-dashed border-gray-400 border-2 rounded flex flex-wrap"},pe=["onClick"],ge=["onClick"],he=["onClick"],me={key:1},fe={key:2,class:"flex h-full"},ye=["innerHTML"],_e={key:3,class:"flex items-center justify-center h-full"},ve={key:4,class:""},be={key:5,class:"flex justify-center max-w-full"},xe={key:0,class:"flex flex-col ms-1 w-100",role:"group"},we=["title","onClick"],ke=["title","onClick"],Ce=["title","onClick"],Ie={"data-te-modal-init":"",class:"fixed left-0 top-0 backdrop-blur z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none",id:"modalFiles",tabindex:"-1","aria-labelledby":"exampleModalLabel","aria-hidden":"true"},De={"data-te-modal-dialog-ref":"",class:"pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out p-5 md:p-10"},Me={class:"min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600"},$e=V('<div class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50"><h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="exampleModalLabel"></h5><button type="button" class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button></div>',1),je={class:"relative flex-auto p-4","data-te-modal-body-ref":""},Te={class:"px-2 flex flex-col md:px-4"},Pe={class:"flex-col bg-white overflow-x-auto shadow-lg rounded-lg"},Ee={class:"flex flex-wrap items-center justify-between py-4 dark:bg-gray-800 p-4"},Fe={class:"flex items-center p-1"},Le={class:"relative mx-1","data-te-dropdown-ref":""},Se={id:"dropdownPaginate","data-te-dropdown-toggle-ref":"","aria-expanded":"false","data-te-ripple-init":"","data-te-ripple-color":"light",class:"inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"},Ae=o("span",{class:"sr-only"},"table actions",-1),Be={ref:"userMenu","data-te-dropdown-menu-ref":"",class:"min-w-[12rem] absolute z-[1000] start-0 text-gray-500 m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-start text-base shadow-lg dark:bg-neutral-700 [&[data-te-dropdown-show]]:block",tabindex:"-1",role:"menu","aria-orientation":"vertical","aria-label":"User menu","aria-labelledby":"dropdownPaginate"},Ve={class:""},Ue=["onClick"],Ne=o("hr",{class:"border-gray-200 dark:border-gray-700"},null,-1),ze={class:"relative p-1"},Ge=o("label",{for:"table-search",class:"sr-only"},"Search",-1),He={class:"absolute inset-y-0 cursor-pointer text-gray-500 hover:text-gray-700 start-0 flex items-center px-3"},qe=["placeholder"],Qe={class:"w-full text-sm text-left text-gray-500 dark:text-gray-400"},Xe={class:"text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"},Je={class:"text-sm text-center"},Ke={class:"flex items-center justify-center"},Oe={class:"px-2"},Re={class:"flex items-center justify-center"},We={class:"px-2"},Ye={class:""},Ze={class:"animate-pulse bg-white text-center border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"},et=o("td",{class:"w-4 p-4"},[o("div",{class:"flex items-center"},[o("input",{id:"checkbox-table-search-1",type:"checkbox",class:"w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"})])],-1),tt=o("td",{class:"flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white"},[o("div",{class:"w-10 h-10 rounded-full"}),o("div",{class:"px-3"},[o("div",{class:"text-base bg-gray-200 px-5 py-2 rounded-lg"}),o("div",{class:"font-normal text-gray-500"})])],-1),ot=o("td",{class:"px-2 py-4"},[o("div",{class:"bg-gray-200 px-5 py-2 rounded-lg"})],-1),rt=[et,tt,ot],st=["onClick"],nt={class:"flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white"},at={class:"text-base font-semibold"},lt={class:"font-normal text-gray-500"},it={class:"px-2 py-4"},dt=o("div",{class:"flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50"},null,-1),ct={key:1},ut={class:"flex my-4"},pt={class:"grow"},gt={key:0,class:"h-24 border border-dashed border-gray-400 border-2 rounded flex flex-wrap"},ht=["onClick"],mt=["onClick"],ft=["onClick"],yt={key:1},_t=["innerHTML"],vt={key:2,class:"flex items-center justify-center h-full"},bt={key:3,class:""},xt={key:4,class:"flex justify-center max-w-full"};function wt(e,r,p,kt,l,c){const k=d("QuestionMarkCircleIcon"),C=d("Tooltip"),I=d("InputLabel"),y=d("XMarkIcon"),D=d("TextEditor"),_=d("Podcast"),v=d("Video"),b=d("Banner"),M=d("InputError"),$=d("ChevronUpIcon"),x=d("ChevronDownIcon"),j=d("PlusIcon"),T=d("Pagination"),P=d("MagnifyingGlassIcon"),w=d("ArrowsUpDownIcon"),E=d("Image");return p.mode!="view"?(s(),n("div",le,[o("div",ie,[a(C,{class:"p-2",content:e.__("help_article_content")},{default:L(()=>[a(k,{class:"text-gray-500 hover:bg-gray-50 w-4 h-4"})]),_:1},8,["content"]),a(I,{class:"my-2",value:p.placeholder},null,8,["value"])]),(s(!0),n(m,null,f(l.cells,(t,i)=>(s(),n("div",{class:"my-2",key:`${t.key}`},[o("div",de,[o("div",ce,[t.type==null?(s(),n("div",ue,[o("div",{onClick:h=>{t.type="content",t.id=Date.now()},class:"grow hover:bg-gray-200 cursor-pointer flex flex-col items-center justify-center"},[o("div",null,g(e.__("text")),1)],8,pe),o("div",{class:"grow hover:bg-gray-200 cursor-pointer flex flex-col items-center justify-center",onClick:h=>c.showFilesModal(i)},[o("div",null,g(e.__("file")),1)],8,ge),o("div",{class:"hover:bg-danger-200 cursor-pointer flex flex-col items-center justify-center",onClick:h=>c.clear(i)},[a(y,{class:"w-6 h-6 m-2 text-gray text-danger"})],8,he)])):u("",!0),t.type=="content"?(s(),n("div",me,[a(D,{mode:"create",preload:t.value,lang:e.$page.props.locale,id:`editor-${t.id}`,ref_for:!0,ref:`text-${t.id}`},null,8,["preload","lang","id"])])):u("",!0),t.type=="text"?(s(),n("div",fe,[o("div",{class:"border border-gray-400 rounded p-2 text-gray-500 w-full",innerHTML:t.value},null,8,ye)])):u("",!0),t.type=="podcast"?(s(),n("div",_e,[a(_,{classes:"  rounded  ","for-id":i,preload:{name:t.value,url:e.route(`storage.${t.type}s`)+`/${t.id}.mp3`,cover:e.route(`storage.${t.type}s`)+`/${t.id}.jpg`},view:"linear",mode:"multi",ref_for:!0,ref:`podcast-${t.id}`},null,8,["for-id","preload"])])):u("",!0),t.type=="video"?(s(),n("div",ve,[a(v,{classes:"  rounded w-full h-64 ","for-id":i,preload:{name:t.value,url:e.route(`storage.${t.type}s`)+`/${t.id}.mp4`},view:"linear",mode:"view",ref_for:!0,ref:`video-${t.id}`},null,8,["for-id","preload"])])):u("",!0),t.type=="banner"?(s(),n("div",be,[a(b,{classes:"rounded  object-contain     ","for-id":i,preload:{name:t.value,url:e.route(`storage.${t.type}s`)+`/${t.id}.jpg`},view:"linear",mode:"view",ref_for:!0,ref:`banner-${t.id}`},null,8,["for-id","preload"])])):u("",!0),a(M,{class:"mt-1",message:p.error},null,8,["message"])]),(p.mode=="edit"||p.mode=="create")&&t.type?(s(),n("div",xe,[o("div",{title:e.__("move_up"),class:"text-center flex rounded-t grow cursor-pointer bg-primary-500 hover:bg-primary-600 p-2 bg-danger text-white",onClick:h=>c.move("up",i)},[a($,{class:"w-4 h-4 mx-auto text-white"})],8,we),o("div",{class:"text-center flex grow cursor-pointer hover:bg-danger-600 p-2 bg-danger text-white",title:e.__("remove"),onClick:h=>c.clear(i)},[l.removing?u("",!0):(s(),U(y,{key:0,class:"w-4 h-4 mx-auto text-white"}))],8,ke),o("div",{title:e.__("move_down"),class:"text-center flex rounded-b grow cursor-pointer bg-primary-500 hover:bg-primary-600 p-2 bg-danger text-white",onClick:h=>c.move("down",i)},[a(x,{class:"w-4 h-4 mx-auto text-white"})],8,Ce)])):u("",!0)]),i==l.cells.length-1?(s(),n("div",{key:0,onClick:r[0]||(r[0]=h=>{l.cells.push({key:Date.now(),type:null,id:null,value:null})}),class:"mt-2 border border-dashed border-gray-400 border-2 rounded flex flex-wrap text-center cursor-pointer hover:bg-gray-200"},[a(j,{class:"w-6 h-6 mx-auto m-2 text-gray-500"})])):u("",!0)]))),128)),o("div",Ie,[o("div",De,[o("div",Me,[$e,o("div",je,[o("div",Te,[o("div",Pe,[o("div",Ee,[o("div",Fe,[o("div",Le,[o("button",Se,[Ae,o("span",null,g(l.params.paginate),1),a(x,{class:"h-4 w-4 mx-1"})]),o("div",Be,[(s(!0),n(m,null,f(e.$page.props.pageItems,t=>(s(),n("div",Ve,[o("div",{onClick:i=>{l.params.paginate=t,c.getData()},role:"menuitem",class:"cursor-pointer select-none block p-2 px-6 text-sm transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"},g(t),9,Ue),Ne]))),256))],512)]),a(T,{onPaginationChanged:c.paginationChanged,pagination:l.pagination},null,8,["onPaginationChanged","pagination"])]),o("div",ze,[Ge,o("div",He,[a(P,{onClick:r[1]||(r[1]=t=>c.getData()),class:"w-4 h-4 dark:text-gray-400"})]),o("div",{class:"absolute inset-y-0 end-0 text-gray-500 flex items-center px-3 cursor-pointer hover:text-gray-700",onClick:r[2]||(r[2]=t=>{l.params.search=null,c.getData()})},[a(y,{class:"w-4 h-4 dark:text-gray-400"})]),S(o("input",{type:"text",id:"table-search-users","onUpdate:modelValue":r[3]||(r[3]=t=>l.params.search=t),onKeydown:r[4]||(r[4]=B(N(t=>c.getData(),["prevent"]),["enter"])),class:"block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500",placeholder:e.__("search")},null,40,qe),[[A,l.params.search]])])]),o("table",Qe,[o("thead",Xe,[o("tr",Je,[o("th",{scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]",onClick:r[5]||(r[5]=t=>{l.params.order_by="name",l.params.dir=l.params.dir=="ASC"?"DESC":"ASC",l.params.page=1,c.getData()})},[o("div",Ke,[o("span",Oe,g(e.__("name")),1),a(w,{class:"w-4 h-4"})])]),o("th",{scope:"col",class:"px-2 py-3 cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]",onClick:r[6]||(r[6]=t=>{l.params.order_by="view",l.params.dir=l.params.dir=="ASC"?"DESC":"ASC",l.params.page=1,c.getData()})},[o("div",Re,[o("span",We,g(e.__("type")),1),a(w,{class:"w-4 h-4"})])])])]),o("tbody",Ye,[l.loading?(s(),n(m,{key:0},f(3,t=>o("tr",Ze,rt)),64)):u("",!0),(s(!0),n(m,null,f(l.data,(t,i)=>(s(),n("tr",{class:"cursor-pointer hover:bg-gray-400 bg-white text-center border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600",onClick:h=>c.selectItem(t)},[o("td",nt,[a(E,{class:"w-10 h-10 rounded-full",src:t.type!="text"?`${e.route(`storage.${t.type}s`)}/${t.id}.jpg`:"",alt:e.cropText(t.name,5)},null,8,["src","alt"]),o("div",at,g(e.cropText(t.name,40)),1),o("div",lt,g(),1)]),o("td",it,g(e.__(t.type)),1)],8,st))),256))])])])])]),dt])])])])):(s(),n("div",ct,[(s(!0),n(m,null,f(l.cells,(t,i)=>(s(),n("div",{class:"my-2",key:`${t.key}`},[o("div",ut,[o("div",pt,[p.mode=="create"&&t.type==null?(s(),n("div",gt,[o("div",{onClick:h=>{t.type="content",t.id=Date.now()},class:"grow hover:bg-gray-200 cursor-pointer flex flex-col items-center justify-center"},[o("div",null,g(e.__("text")),1)],8,ht),o("div",{class:"grow hover:bg-gray-200 cursor-pointer flex flex-col items-center justify-center",onClick:h=>c.showFilesModal(i)},[o("div",null,g(e.__("file")),1)],8,mt),o("div",{class:"hover:bg-danger-200 cursor-pointer flex flex-col items-center justify-center",onClick:h=>c.clear(i)},[a(y,{class:"w-6 h-6 m-2 text-gray text-danger"})],8,ft)])):u("",!0),t.type=="text"||t.type=="content"?(s(),n("div",yt,[o("div",{innerHTML:t.value},null,8,_t)])):u("",!0),t.type=="podcast"?(s(),n("div",vt,[a(_,{classes:"  rounded  ","for-id":i,preload:{name:t.value,url:e.route(`storage.${t.type}s`)+`/${t.id}.mp3`,cover:e.route(`storage.${t.type}s`)+`/${t.id}.jpg`},view:"linear",mode:"multi",ref_for:!0,ref:`podcast-${t.id}`},null,8,["for-id","preload"])])):u("",!0),t.type=="video"?(s(),n("div",bt,[a(v,{classes:"  rounded w-full h-64 ","for-id":i,preload:{name:t.value,url:e.route(`storage.${t.type}s`)+`/${t.id}.mp4`},view:"linear",mode:"view",ref_for:!0,ref:`video-${t.id}`},null,8,["for-id","preload"])])):u("",!0),t.type=="banner"?(s(),n("div",xt,[a(b,{classes:"rounded  object-contain     ","for-id":i,preload:{name:t.value,url:e.route(`storage.${t.type}s`)+`/${t.id}.jpg`},view:"linear",mode:"view",ref_for:!0,ref:`banner-${t.id}`},null,8,["for-id","preload"])])):u("",!0)])])]))),128))]))}const Nt=Z(ae,[["render",wt]]);export{Nt as A};
