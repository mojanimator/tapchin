import"./Scaffold-D_ifbLkl.js";import{P as V}from"./Panel-rs2tkraC.js";import{T as P,Z as T,j as U,i as n,o as i,c as v,w as m,a as s,t as c,b as l,f as a,l as u,e as p,n as f,p as B}from"./app-DeJr7fNP.js";import{_ as E}from"./Checkbox-BWgqGgzP.js";import"./GuestLayout-B84nh8OP.js";import{_ as A,a as j}from"./InputLabel-C2gVT93H.js";import{_ as D}from"./PrimaryButton-Fk5jf8Nh.js";import{_ as F}from"./TextInput-BUWn7uYR.js";import{R as L}from"./RadioGroup-F_Tqn1wR.js";import{L as M}from"./LoadingIcon-8dx7WYGr.js";import{P as N}from"./Popover-BaS0lwjX.js";import{T as X}from"./Tooltip-CSg56dN2.js";import{_ as R}from"./TagInput-3zOTNi0B.js";import{I as z}from"./ImageUploader-v1nOBbO9.js";import{S as H}from"./Selector-NdNaM2dQ.js";import{P as q}from"./ProvinceCounty-Bztk6mkC.js";import{P as G}from"./PhoneFields-Dp_fguC6.js";import{S as O}from"./SocialFields-hQVy6EEa.js";import{T as Q}from"./TextEditor-7zAQTOol.js";import{U as Z}from"./UserSelector-D8GJCG_Y.js";import{A as J}from"./AddressSelector-BkUCPfBb.js";import{C as K}from"./CitySelector-BCnZf9JU.js";import{_ as W}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{a as Y}from"./ShoppingBagIcon-zIu7FSuq.js";import{r as $}from"./Footer-BYUJXMx0.js";import{r as ee}from"./Alert-CDDSmLjy.js";import{r as oe}from"./EyeIcon-DI71KuzS.js";import{r as re}from"./FolderPlusIcon-oBdmLyEF.js";import{r as se}from"./Bars2Icon-DNQAv7Yb.js";import{r as te}from"./ChatBubbleBottomCenterTextIcon-CovOeJqa.js";import{r as le}from"./QuestionMarkCircleIcon-jeUFMgsk.js";import{r as ne}from"./Squares2X2Icon-Dviu76Pe.js";import{r as ie}from"./SignalIcon-C7ONQsBK.js";import{r as me}from"./PencilIcon-BtrTq_9n.js";import{r as ae}from"./CartItemButton-BUsVs_zz.js";import"./ApplicationLogo-CV6pwTdm.js";import"./WrenchScrewdriverIcon-DQFoZ8JW.js";import"./PlusIcon-NsBgz0vi.js";import"./XMarkIcon-CEnCPALs.js";import"./PhoneIcon-CdiwYJQQ.js";import"./LinkIcon-ClPrNMD5.js";import"./MinusIcon-B-7x8Y4K.js";import"./Pagination-VNHzEPuv.js";import"./ChevronRightIcon-9GRJ1f_S.js";import"./MagnifyingGlassIcon--KPgqpVy.js";import"./geosearch.module--3h6cvjB.js";import"./SearchInput-CiaqlZI_.js";import"./MapPinIcon-DUvwgUpM.js";import"./MapIcon-CJ5EAjl4.js";import"./TrashIcon-BFmLih4l.js";import"./ChevronUpIcon-DGI4GkYP.js";const ce={data(){return{form:P({agency_id:null,admin_id:null,type_id:null,is_shop:!1,allow_visit:!1,name:null,address:null,lat:null,lon:null,location:null,province_id:null,county_id:null,district_id:null,postal_code:null,phone:null,cities:[]}),img:null}},components:{AddressSelector:J,UserSelector:Z,ImageUploader:z,LoadingIcon:M,Head:T,Link:U,HomeIcon:Y,ChevronDownIcon:$,Panel:V,InputLabel:A,TextInput:F,InputError:j,PrimaryButton:D,RadioGroup:L,UserIcon:ee,EyeIcon:oe,Checkbox:E,Popover:N,Tooltip:X,FolderPlusIcon:re,Bars2Icon:se,ChatBubbleBottomCenterTextIcon:te,TagInput:R,QuestionMarkCircleIcon:le,Selector:H,Squares2X2Icon:ne,ProvinceCounty:q,PhoneFields:G,SocialFields:O,SignalIcon:ie,TextEditor:Q,PencilIcon:me,XMarkIcon:ae,CitySelector:K},mounted(){},watch:{form(o,t){}},methods:{updateAddress(o){o=o||{},this.form.address=o.address,this.form.province_id=o.province_id,this.form.county_id=o.county_id,this.form.district_id=o.district_id,this.form.lat=o.lat,this.form.lon=o.lon,this.form.location=`${o.lat},${o.lon}`,this.form.postal_code=this.f2e(o.postal_code)},submit(){this.form.clearErrors(),this.form.phone=this.f2e(this.form.phone),this.form.post(route("admin.panel.repository.create"),{preserveScroll:!1,onSuccess:o=>{this.$page.props.flash.status&&this.showAlert(this.$page.props.flash.status,this.$page.props.flash.message)},onError:()=>{this.showToast("danger",Object.values(this.form.errors).join("<br/>"))},onFinish:o=>{}})}}},pe={class:"flex items-center justify-start px-4 py-2 text-primary-500 border-b md:py-4"},de={class:"text-2xl font-semibold"},_e={class:"px-2 md:px-4"},fe={class:"mx-auto md:max-w-2xl mt-6 px-2 md:px-4 py-4 bg-white shadow-md overflow-hidden rounded-lg"},ue={class:"flex flex-col mx-2 col-span-2 w-full px-2"},he={class:"flex-col m-2 items-center rounded-lg max-w-xs w-full mx-auto"};const ge={key:0,class:"my-2"},ye={class:"grow"},ve=["onClick"],be={class:"my-2"},we={class:"grow"},xe=["onClick"],Ie={class:"my-2"},ke={class:"p-3"},Ce={class:"my-2"},Se={class:"p-3"},Ve={class:"my-4"},Pe={class:"my-4"},Te={class:"my-3"},Ue={key:1,class:"my-3"},Be={key:2,class:"shadow w-full bg-grey-light m-2 bg-gray-200 rounded-full"},Ee={class:"animate-bounce"},Ae={class:"mt-4"},je={class:"text-lg"};function De(o,t,Fe,Le,e,d){const b=n("FolderPlusIcon"),Me=n("ImageUploader"),Ne=n("InputError"),h=n("XMarkIcon"),g=n("UserSelector"),y=n("Bars2Icon"),_=n("TextInput"),w=n("AddressSelector"),x=n("CitySelector"),I=n("LoadingIcon"),k=n("PrimaryButton"),C=n("Panel");return i(),v(C,null,{header:m(()=>[s("title",null,c(o.__("new_repository")),1)]),content:m(()=>[s("div",pe,[l(b,{class:"h-7 w-7 mx-3"}),s("h1",de,c(o.__("new_repository")),1)]),s("div",_e,[s("div",fe,[s("div",ue,[s("div",he,[a("",!0)]),s("form",{onSubmit:t[8]||(t[8]=u((...r)=>d.submit&&d.submit(...r),["prevent"]))},[o.$page.props.agency&&o.$page.props.agency.level<3?(i(),p("div",ge,[l(g,{colsData:["name","phone","level"],labelsData:["name","phone","type"],callback:{level:o.getAgency},error:e.form.errors.agency_id,link:o.route("admin.panel.agency.search"),label:o.__("agency"),id:"agency",selected:e.form.agency_id,"onUpdate:selected":t[0]||(t[0]=r=>e.form.agency_id=r),preload:null},{selector:m(r=>[s("div",{class:f([(r.selectedText,"py-2"),"px-4 border border-gray-300 rounded hover:bg-gray-100 cursor-pointer flex items-center"])},[s("div",ye,c(r.selectedText??o.__("select")),1),r.selectedText?(i(),p("div",{key:0,class:"bg-danger rounded p-2 cursor-pointer text-white hover:bg-danger-400",onClick:u(S=>r.clear(),["stop"])},[l(h,{class:"w-5 h-5"})],8,ve)):a("",!0)],2)]),_:1},8,["callback","error","link","label","selected"])])):a("",!0),s("div",be,[l(g,{colsData:["fullname","phone","agency_id"],labelsData:["name","phone","agency_id"],link:o.route("admin.panel.admin.search")+(e.form.agency_id?`?agency_id=${e.form.agency_id}`:""),label:o.__("repo_owner/admin"),error:e.form.errors.admin_id,id:"admin",selected:e.form.admin_id,"onUpdate:selected":t[1]||(t[1]=r=>e.form.admin_id=r),preload:null},{selector:m(r=>[s("div",{class:f([(r.selectedText,"py-2"),"px-4 border border-gray-300 rounded hover:bg-gray-100 cursor-pointer flex items-center"])},[s("div",we,c(r.selectedText??o.__("select")),1),r.selectedText?(i(),p("div",{key:0,class:"bg-danger rounded p-2 cursor-pointer text-white hover:bg-danger-400",onClick:u(S=>r.clear(),["stop"])},[l(h,{class:"w-5 h-5"})],8,xe)):a("",!0)],2)]),_:1},8,["link","label","error","selected"])]),s("div",Ie,[l(_,{id:"name",type:"text",placeholder:o.__("repo_name"),classes:"  ",modelValue:e.form.name,"onUpdate:modelValue":t[2]||(t[2]=r=>e.form.name=r),autocomplete:"name",error:e.form.errors.name},{prepend:m(()=>[s("div",ke,[l(y,{class:"h-5 w-5"})])]),_:1},8,["placeholder","modelValue","error"])]),s("div",Ce,[l(_,{id:"phone",type:"tel",placeholder:o.__("phone"),classes:"  ",modelValue:e.form.phone,"onUpdate:modelValue":t[3]||(t[3]=r=>e.form.phone=r),autocomplete:"phone",error:e.form.errors.phone},{prepend:m(()=>[s("div",Se,[l(y,{class:"h-5 w-5"})])]),_:1},8,["placeholder","modelValue","error"])]),s("div",Ve,[l(w,{editable:!0,clearable:!0,class:"",type:"",label:o.__("address"),onChange:t[4]||(t[4]=r=>d.updateAddress(r)),error:e.form.errors.address||e.form.errors.postal_code||e.form.errors.province_id||e.form.errors.county_id},null,8,["label","error"])]),s("div",Pe,[l(x,{multi:!0,label:o.__("supported_districts"),modelValue:e.form.cities,"onUpdate:modelValue":t[5]||(t[5]=r=>e.form.cities=r),error:e.form.errors.cities},null,8,["label","modelValue","error"])]),s("div",Te,[l(_,{id:"is_shop",type:"checkbox",placeholder:o.__("connect_shop"),classes:"  ",modelValue:e.form.is_shop,"onUpdate:modelValue":t[6]||(t[6]=r=>e.form.is_shop=r),autocomplete:"is_shop",error:e.form.errors.is_shop},null,8,["placeholder","modelValue","error"])]),e.form.is_shop?(i(),p("div",Ue,[l(_,{id:"allow_visit",type:"checkbox",placeholder:o.__("allow_visit"),classes:"  ",modelValue:e.form.allow_visit,"onUpdate:modelValue":t[7]||(t[7]=r=>e.form.allow_visit=r),autocomplete:"allow_visit",error:e.form.errors.allow_visit},null,8,["placeholder","modelValue","error"])])):a("",!0),e.form.progress?(i(),p("div",Be,[s("div",{class:f(["bg-primary rounded text-xs leading-none py-[.1rem] text-center text-white duration-300",{" animate-pulse":e.form.progress.percentage<100}]),style:B(`width: ${e.form.progress.percentage}%`)},[s("span",Ee,c(e.form.progress.percentage),1)],6)])):a("",!0),s("div",Ae,[l(k,{onClick:d.submit,type:"button",class:f(["w-full flex items-center justify-center",{"opacity-25":e.form.processing}]),disabled:e.form.processing},{default:m(()=>[e.form.processing?(i(),v(I,{key:0,class:"w-4 h-4 mx-3"})):a("",!0),s("span",je,c(o.__("register_info")),1)]),_:1},8,["onClick","class","disabled"])])],32)])])])]),_:1})}const Mo=W(ce,[["render",De]]);export{Mo as default};
