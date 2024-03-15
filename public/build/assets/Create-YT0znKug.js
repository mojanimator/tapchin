import"./Scaffold-D_ifbLkl.js";import{P as T}from"./Panel-rs2tkraC.js";import{T as C,Z as S,j as B,i as t,o as l,c as d,w as i,a as o,t as m,b as s,l as V,e as E,n as f,p as k,f as u}from"./app-DeJr7fNP.js";import{_ as j}from"./Checkbox-BWgqGgzP.js";import"./GuestLayout-B84nh8OP.js";import{_ as F,a as U}from"./InputLabel-C2gVT93H.js";import{_ as $}from"./PrimaryButton-Fk5jf8Nh.js";import{_ as L}from"./TextInput-BUWn7uYR.js";import{R as D}from"./RadioGroup-F_Tqn1wR.js";import{L as N}from"./LoadingIcon-8dx7WYGr.js";import{P as R}from"./Popover-BaS0lwjX.js";import{T as z}from"./Tooltip-CSg56dN2.js";import{_ as H}from"./TagInput-3zOTNi0B.js";import{I as M}from"./ImageUploader-v1nOBbO9.js";import{S as O}from"./Selector-NdNaM2dQ.js";import{P as q}from"./ProvinceCounty-Bztk6mkC.js";import{P as A}from"./PhoneFields-Dp_fguC6.js";import{S as G}from"./SocialFields-hQVy6EEa.js";import{T as Q}from"./TextEditor-7zAQTOol.js";import{_ as X}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{a as Z}from"./ShoppingBagIcon-zIu7FSuq.js";import{r as J}from"./Footer-BYUJXMx0.js";import{r as K}from"./Alert-CDDSmLjy.js";import{r as W}from"./EyeIcon-DI71KuzS.js";import{r as Y}from"./FolderPlusIcon-oBdmLyEF.js";import{r as oo}from"./Bars2Icon-DNQAv7Yb.js";import{r as eo}from"./ChatBubbleBottomCenterTextIcon-CovOeJqa.js";import{r as ro}from"./QuestionMarkCircleIcon-jeUFMgsk.js";import{r as to}from"./Squares2X2Icon-Dviu76Pe.js";import{r as so}from"./SignalIcon-C7ONQsBK.js";import{r as no}from"./PencilIcon-BtrTq_9n.js";import"./ApplicationLogo-CV6pwTdm.js";import"./CartItemButton-BUsVs_zz.js";import"./ChevronUpIcon-DGI4GkYP.js";import"./PlusIcon-NsBgz0vi.js";import"./MinusIcon-B-7x8Y4K.js";import"./WrenchScrewdriverIcon-DQFoZ8JW.js";import"./XMarkIcon-CEnCPALs.js";import"./PhoneIcon-CdiwYJQQ.js";import"./LinkIcon-ClPrNMD5.js";const ao={data(){return{form:C({lang:null,author:null,title:null,content:null,category_id:null,tags:"",summary:""}),img:null}},components:{ImageUploader:M,LoadingIcon:N,Head:S,Link:B,HomeIcon:Z,ChevronDownIcon:J,Panel:T,InputLabel:F,TextInput:L,InputError:U,PrimaryButton:$,RadioGroup:D,UserIcon:K,EyeIcon:W,Checkbox:j,Popover:R,Tooltip:z,FolderPlusIcon:Y,Bars2Icon:oo,ChatBubbleBottomCenterTextIcon:eo,TagInput:H,QuestionMarkCircleIcon:ro,Selector:O,Squares2X2Icon:to,ProvinceCounty:q,PhoneFields:A,SocialFields:G,SignalIcon:so,TextEditor:Q,PencilIcon:no},mounted(){},methods:{submit(){this.img=this.$refs.imageCropper.getCroppedData(),this.form.content=this.$refs.editor.getData(),this.form.uploading=!1,this.form.clearErrors(),this.form.post(route("panel.admin.article.create"),{preserveScroll:!1,onSuccess:n=>{this.form.uploading||(this.form.uploading=!0,this.form.transform(r=>({...r,uploading:!0,img:this.img})).post(route("panel.admin.article.create"),{preserveScroll:!1,onSuccess:r=>{this.$page.props.flash.status&&this.showAlert(this.$page.props.flash.status,this.$page.props.flash.message)},onError:()=>{this.showToast("danger",Object.values(this.form.errors).join("<br/>"))},onFinish:r=>{}}))},onError:()=>{this.showToast("danger",Object.values(this.form.errors).join("<br/>"))},onFinish:n=>{}})}},watch:{}},io={class:"flex items-center justify-start px-4 py-2 text-primary-500 border-b md:py-4"},mo={class:"text-2xl font-semibold"},lo={class:"px-2 md:px-4"},po={class:"mx-auto md:max-w-5xl mt-6 px-2 md:px-4 py-4 bg-white shadow-md overflow-hidden rounded-lg"},co={class:"flex flex-col mx-2 col-span-2 w-full px-2"},fo={class:"flex-col m-2 items-center rounded-lg max-w-xs w-full mx-auto"},uo={class:"my-2"},_o={class:"my-2"},ho={class:"p-3"},go={class:"my-2"},xo={class:"p-3"},Io={class:"my-2"},yo={class:"my-2"},wo={key:0,class:"shadow w-full bg-grey-light m-2 bg-gray-200 rounded-full"},vo={class:"animate-bounce"},bo={class:"mt-4"},Po={class:"text-lg"};function To(n,r,Co,So,e,p){const _=t("FolderPlusIcon"),h=t("ImageUploader"),g=t("InputError"),x=t("Bars2Icon"),c=t("TextInput"),I=t("PencilIcon"),y=t("TagInput"),w=t("TextEditor"),v=t("LoadingIcon"),b=t("PrimaryButton"),P=t("Panel");return l(),d(P,null,{header:i(()=>[o("title",null,m(n.__("new_article")),1)]),content:i(()=>[o("div",io,[s(_,{class:"h-7 w-7 mx-3"}),o("h1",mo,m(n.__("new_article")),1)]),o("div",lo,[o("div",po,[o("div",co,[o("div",fo,[o("div",uo,[s(h,{ref:"imageCropper",label:n.__("image_cover_jpg"),cropRatio:"1.25",id:"img",height:"10",class:"grow"},null,8,["label"]),s(g,{class:"mt-1",message:e.form.errors.img},null,8,["message"])])]),o("form",{onSubmit:r[3]||(r[3]=V((...a)=>p.submit&&p.submit(...a),["prevent"]))},[o("div",_o,[s(c,{id:"title",type:"text",placeholder:n.__("title"),classes:"  ",modelValue:e.form.title,"onUpdate:modelValue":r[0]||(r[0]=a=>e.form.title=a),autocomplete:"title",error:e.form.errors.title},{prepend:i(()=>[o("div",ho,[s(x,{class:"h-5 w-5"})])]),_:1},8,["placeholder","modelValue","error"])]),o("div",go,[s(c,{id:"author",type:"text",placeholder:n.__("author"),classes:"  ",modelValue:e.form.author,"onUpdate:modelValue":r[1]||(r[1]=a=>e.form.author=a),autocomplete:"author",error:e.form.errors.author},{prepend:i(()=>[o("div",xo,[s(I,{class:"h-5 w-5"})])]),_:1},8,["placeholder","modelValue","error"])]),o("div",Io,[s(y,{id:"tags",placeholder:n.__("tags"),classes:"  ",modelValue:e.form.tags,"onUpdate:modelValue":r[2]||(r[2]=a=>e.form.tags=a),autocomplete:"tags",error:e.form.errors.tags},null,8,["placeholder","modelValue","error"])]),o("div",yo,[s(w,{mode:"create",lang:"fa",id:"editor",ref:"editor"})]),e.form.progress?(l(),E("div",wo,[o("div",{class:f(["bg-primary rounded text-xs leading-none py-[.1rem] text-center text-white duration-300",{" animate-pulse":e.form.progress.percentage<100}]),style:k(`width: ${e.form.progress.percentage}%`)},[o("span",vo,m(e.form.progress.percentage),1)],6)])):u("",!0),o("div",bo,[s(b,{class:f(["w-full",{"opacity-25":e.form.processing}]),disabled:e.form.processing},{default:i(()=>[e.form.processing?(l(),d(v,{key:0,class:"w-4 h-4 mx-3"})):u("",!0),o("span",Po,m(n.__("register_info")),1)]),_:1},8,["class","disabled"])])],32)])])])]),_:1})}const ue=X(ao,[["render",To]]);export{ue as default};
