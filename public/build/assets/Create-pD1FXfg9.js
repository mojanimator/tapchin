import"./Scaffold-D_ifbLkl.js";import{P as S}from"./Panel-rs2tkraC.js";import{T as C,Z as P,j as U,i,o as a,c as _,w as p,a as s,t as n,b as l,l as g,n as u,e as h,f,p as T}from"./app-DeJr7fNP.js";import{_ as B}from"./Checkbox-BWgqGgzP.js";import"./GuestLayout-B84nh8OP.js";import{_ as E,a as j}from"./InputLabel-C2gVT93H.js";import{_ as F}from"./PrimaryButton-Fk5jf8Nh.js";import{_ as L}from"./TextInput-BUWn7uYR.js";import{R as D}from"./RadioGroup-F_Tqn1wR.js";import{L as M}from"./LoadingIcon-8dx7WYGr.js";import{P as A}from"./Popover-BaS0lwjX.js";import{T as X}from"./Tooltip-CSg56dN2.js";import{_ as N}from"./TagInput-3zOTNi0B.js";import{I as R}from"./ImageUploader-v1nOBbO9.js";import{S as z}from"./Selector-NdNaM2dQ.js";import{P as H}from"./ProvinceCounty-Bztk6mkC.js";import{P as O}from"./PhoneFields-Dp_fguC6.js";import{S as q}from"./SocialFields-hQVy6EEa.js";import{T as G}from"./TextEditor-7zAQTOol.js";import{U as Q}from"./UserSelector-D8GJCG_Y.js";import{A as Z}from"./AddressSelector-BkUCPfBb.js";import{C as J}from"./CitySelector-BCnZf9JU.js";import{_ as K}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{a as W}from"./ShoppingBagIcon-zIu7FSuq.js";import{r as Y}from"./Footer-BYUJXMx0.js";import{r as $}from"./Alert-CDDSmLjy.js";import{r as ee}from"./EyeIcon-DI71KuzS.js";import{r as re}from"./FolderPlusIcon-oBdmLyEF.js";import{r as oe}from"./Bars2Icon-DNQAv7Yb.js";import{r as se}from"./ChatBubbleBottomCenterTextIcon-CovOeJqa.js";import{r as te}from"./QuestionMarkCircleIcon-jeUFMgsk.js";import{r as le}from"./Squares2X2Icon-Dviu76Pe.js";import{r as ie}from"./SignalIcon-C7ONQsBK.js";import{r as ne}from"./PencilIcon-BtrTq_9n.js";import{r as me}from"./CartItemButton-BUsVs_zz.js";import"./ApplicationLogo-CV6pwTdm.js";import"./WrenchScrewdriverIcon-DQFoZ8JW.js";import"./PlusIcon-NsBgz0vi.js";import"./XMarkIcon-CEnCPALs.js";import"./PhoneIcon-CdiwYJQQ.js";import"./LinkIcon-ClPrNMD5.js";import"./MinusIcon-B-7x8Y4K.js";import"./Pagination-VNHzEPuv.js";import"./ChevronRightIcon-9GRJ1f_S.js";import"./MagnifyingGlassIcon--KPgqpVy.js";import"./geosearch.module--3h6cvjB.js";import"./SearchInput-CiaqlZI_.js";import"./MapPinIcon-DUvwgUpM.js";import"./MapIcon-CJ5EAjl4.js";import"./TrashIcon-BFmLih4l.js";import"./ChevronUpIcon-DGI4GkYP.js";const ae={data(){return{form:C({product_id:null,repo_id:null,weight:null,grade:null,price:null,pack_id:null,in_repo:null,in_shop:null,uploading:!1}),img:null}},components:{AddressSelector:Z,UserSelector:Q,ImageUploader:R,LoadingIcon:M,Head:P,Link:U,HomeIcon:W,ChevronDownIcon:Y,Panel:S,InputLabel:E,TextInput:L,InputError:j,PrimaryButton:F,RadioGroup:D,UserIcon:$,EyeIcon:ee,Checkbox:B,Popover:A,Tooltip:X,FolderPlusIcon:re,Bars2Icon:oe,ChatBubbleBottomCenterTextIcon:se,TagInput:N,QuestionMarkCircleIcon:te,Selector:z,Squares2X2Icon:le,ProvinceCounty:H,PhoneFields:O,SocialFields:q,SignalIcon:ie,TextEditor:G,PencilIcon:ne,XMarkIcon:me,CitySelector:J},mounted(){},watch:{form(t,o){}},methods:{submit(){this.img=this.$refs.imageCropper.getCroppedData(),this.form.uploading=!1,this.form.clearErrors(),this.form.post(route("admin.panel.variation.create"),{preserveScroll:!1,onSuccess:t=>{this.form.uploading||(this.form.uploading=!0,this.form.transform(o=>({...o,uploading:!0,img:this.img})).post(route("admin.panel.variation.create"),{preserveScroll:!1,onSuccess:o=>{this.$page.props.flash.status&&this.showAlert(this.$page.props.flash.status,this.$page.props.flash.message)},onError:()=>{this.showToast("danger",Object.values(this.form.errors).join("<br/>"))},onFinish:o=>{}}))},onError:()=>{this.showToast("danger",Object.values(this.form.errors).join("<br/>"))},onFinish:t=>{}})}}},pe={class:"flex items-center justify-start px-4 py-2 text-primary-500 border-b md:py-4"},de={class:"text-2xl font-semibold"},ce={class:"px-2 md:px-4"},ue={class:"mx-auto md:max-w-2xl mt-6 px-2 md:px-4 py-4 bg-white shadow-md overflow-hidden rounded-lg"},fe={class:"flex flex-col mx-2 col-span-2 w-full px-2"},_e={class:"flex-col m-2 items-center rounded-lg max-w-xs w-full mx-auto"},ge={class:"my-2"},he={class:"my-2"},be={class:"grow"},ye=["onClick"],we={class:"my-2"},ve={class:"my-2"},Ie={class:"my-2"},ke={class:"my-2"},xe={class:"my-2"},Ve={class:"my-2"},Se={key:0,class:"shadow w-full bg-grey-light m-2 bg-gray-200 rounded-full"},Ce={class:"animate-bounce"},Pe={class:"mt-4"},Ue={class:"text-lg"};function Te(t,o,Be,Ee,e,d){const b=i("FolderPlusIcon"),y=i("ImageUploader"),w=i("InputError"),v=i("XMarkIcon"),I=i("UserSelector"),c=i("Selector"),m=i("TextInput"),k=i("LoadingIcon"),x=i("PrimaryButton"),V=i("Panel");return a(),_(V,null,{header:p(()=>[s("title",null,n(t.__("new_product")),1)]),content:p(()=>[s("div",pe,[l(b,{class:"h-7 w-7 mx-3"}),s("h1",de,n(t.__("new_product")),1)]),s("div",ce,[s("div",ue,[s("div",fe,[s("div",_e,[s("div",ge,[l(y,{ref:"imageCropper",label:t.__("product_image_jpg"),cropRatio:"1.25",id:"img",height:"10",class:"grow","crop-ratio":1},null,8,["label"]),l(w,{class:"mt-1",message:e.form.errors.img},null,8,["message"])])]),s("form",{onSubmit:o[9]||(o[9]=g((...r)=>d.submit&&d.submit(...r),["prevent"]))},[s("div",he,[l(I,{colsData:["id","name","phone","agency_id"],labelsData:["id","name","phone","agency_id"],callback:{level:t.getAgency},error:e.form.errors.repo_id,link:t.route("admin.panel.repository.search")+"?status=active",label:t.__("repository"),id:"repository",selected:e.form.repo_id,"onUpdate:selected":o[0]||(o[0]=r=>e.form.repo_id=r),preload:null},{selector:p(r=>[s("div",{class:u([(r.selectedText,"py-2"),"px-4 border border-gray-300 rounded hover:bg-gray-100 cursor-pointer flex items-center"])},[s("div",be,n(r.selectedText??t.__("select")),1),r.selectedText?(a(),h("div",{key:0,class:"bg-danger rounded p-2 cursor-pointer text-white hover:bg-danger-400",onClick:g(je=>r.clear(),["stop"])},[l(v,{class:"w-5 h-5"})],8,ye)):f("",!0)],2)]),_:1},8,["callback","error","link","label","selected"])]),l(c,{ref:"productSelector",modelValue:e.form.product_id,"onUpdate:modelValue":o[1]||(o[1]=r=>e.form.product_id=r),data:t.$page.props.products,error:e.form.errors.product_id,label:t.__("product"),classes:"",id:"product_id"},null,8,["modelValue","data","error","label"]),s("div",we,[l(c,{ref:"gradeSelector",modelValue:e.form.grade,"onUpdate:modelValue":o[2]||(o[2]=r=>e.form.grade=r),data:t.$page.props.grades.map(r=>({id:r,name:r})),error:e.form.errors.grade,label:t.__("grade"),classes:"",id:"grade"},null,8,["modelValue","data","error","label"])]),s("div",ve,[l(c,{ref:"packSelector",modelValue:e.form.pack_id,"onUpdate:modelValue":o[3]||(o[3]=r=>e.form.pack_id=r),data:t.$page.props.packs,onChange:o[4]||(o[4]=r=>{e.form.pack_id==1&&(e.form.weight=1)}),error:e.form.errors.pack_id,label:t.__("pack"),classes:"",id:"pack"},null,8,["modelValue","data","error","label"])]),s("div",Ie,[l(m,{id:"weight",type:"number",placeholder:t.__("weight"),disabled:e.form.pack_id==1,classes:" p-2   min-w-[5rem]",modelValue:e.form.weight,"onUpdate:modelValue":o[5]||(o[5]=r=>e.form.weight=r),autocomplete:"weight",error:e.form.errors.weight},null,8,["placeholder","disabled","modelValue","error"])]),s("div",ke,[l(m,{id:"price",type:"number",placeholder:t.__("price"),classes:" p-2   min-w-[5rem]",modelValue:e.form.price,"onUpdate:modelValue":o[6]||(o[6]=r=>e.form.price=r),autocomplete:"price",error:e.form.errors.price},null,8,["placeholder","modelValue","error"])]),s("div",xe,[l(m,{id:"in_repo",type:"number",placeholder:t.__("repository_count"),classes:"    ",modelValue:e.form.in_repo,"onUpdate:modelValue":o[7]||(o[7]=r=>e.form.in_repo=r),autocomplete:"in_repo",error:e.form.errors.in_repo},null,8,["placeholder","modelValue","error"])]),s("div",Ve,[l(m,{id:"in_shop",type:"number",placeholder:t.__("shop_count"),classes:" ",modelValue:e.form.in_shop,"onUpdate:modelValue":o[8]||(o[8]=r=>e.form.in_shop=r),autocomplete:"in_shop",error:e.form.errors.in_shop},null,8,["placeholder","modelValue","error"])]),e.form.progress?(a(),h("div",Se,[s("div",{class:u(["bg-primary rounded text-xs leading-none py-[.1rem] text-center text-white duration-300",{" animate-pulse":e.form.progress.percentage<100}]),style:T(`width: ${e.form.progress.percentage}%`)},[s("span",Ce,n(e.form.progress.percentage),1)],6)])):f("",!0),s("div",Pe,[l(x,{onClick:d.submit,type:"button",class:u(["w-full flex items-center justify-center",{"opacity-25":e.form.processing}]),disabled:e.form.processing},{default:p(()=>[e.form.processing?(a(),_(k,{key:0,class:"w-4 h-4 mx-3"})):f("",!0),s("span",Ue,n(t.__("register_info")),1)]),_:1},8,["onClick","class","disabled"])])],32)])])])]),_:1})}const Br=K(ae,[["render",Te]]);export{Br as default};
