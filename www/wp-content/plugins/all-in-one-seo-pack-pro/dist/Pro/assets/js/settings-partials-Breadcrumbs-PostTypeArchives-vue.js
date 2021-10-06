(window["aioseopjsonp"]=window["aioseopjsonp"]||[]).push([["settings-partials-Breadcrumbs-PostTypeArchives-vue","settings-partials-Breadcrumbs-Preview-vue"],{"1e68":function(e,a,t){"use strict";t.r(a);var s=function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",e._l(e.archives,(function(a){return t("core-settings-row",{key:a.name,attrs:{name:a.label},scopedSlots:e._u([{key:"content",fn:function(){return[t("div",[t("preview",{attrs:{"preview-data":e.getPreview(a),useDefaultTemplate:e.dynamicOptions.breadcrumbs.archives.postTypes[a.name].useDefaultTemplate}}),t("grid-row",[t("grid-column",[t("base-toggle",{staticClass:"current-item",model:{value:e.dynamicOptions.breadcrumbs.archives.postTypes[a.name].useDefaultTemplate,callback:function(t){e.$set(e.dynamicOptions.breadcrumbs.archives.postTypes[a.name],"useDefaultTemplate",t)},expression:"dynamicOptions.breadcrumbs.archives.postTypes[archive.name].useDefaultTemplate"}}),e._v(" "+e._s(e.strings.useDefaultTemplate)+" ")],1)],1),e.dynamicOptions.breadcrumbs.archives.postTypes[a.name].useDefaultTemplate?e._e():t("grid-row",[e.options.breadcrumbs.breadcrumbPrefix&&e.options.breadcrumbs.breadcrumbPrefix.length?t("grid-column",[t("base-toggle",{staticClass:"current-item",model:{value:e.dynamicOptions.breadcrumbs.archives.postTypes[a.name].showPrefixCrumb,callback:function(t){e.$set(e.dynamicOptions.breadcrumbs.archives.postTypes[a.name],"showPrefixCrumb",t)},expression:"dynamicOptions.breadcrumbs.archives.postTypes[archive.name].showPrefixCrumb"}}),e._v(" "+e._s(e.strings.showPrefixLabel)+" ")],1):e._e(),t("grid-column",[t("base-toggle",{staticClass:"current-item",model:{value:e.dynamicOptions.breadcrumbs.archives.postTypes[a.name].showHomeCrumb,callback:function(t){e.$set(e.dynamicOptions.breadcrumbs.archives.postTypes[a.name],"showHomeCrumb",t)},expression:"dynamicOptions.breadcrumbs.archives.postTypes[archive.name].showHomeCrumb"}}),e._v(" "+e._s(e.strings.showHomeLabel)+" ")],1),t("grid-column",[t("core-html-tags-editor",{attrs:{"line-numbers":!0,checkUnfilteredHtml:"","tags-context":"breadcrumbs-post-type-archive-"+a.name,"minimum-line-numbers":3,"default-tags":["breadcrumb_archive_post_type_format","breadcrumb_archive_post_type_name","breadcrumb_link"]},model:{value:e.dynamicOptions.breadcrumbs.archives.postTypes[a.name].template,callback:function(t){e.$set(e.dynamicOptions.breadcrumbs.archives.postTypes[a.name],"template",t)},expression:"dynamicOptions.breadcrumbs.archives.postTypes[archive.name].template"}})],1)],1)],1)]},proxy:!0}],null,!0)})})),1)},r=[],i=t("5530"),n=(t("b0c0"),t("ac1f"),t("5319"),t("4d63"),t("25f0"),t("2f62")),o=t("c468"),c={components:{preview:o["default"]},data:function(){return{strings:{useDefaultTemplate:this.$t.__("Use a default template",this.$td),showHomeLabel:this.$t.__("Show homepage link",this.$td),showPrefixLabel:this.$t.__("Show prefix link",this.$td)}}},methods:{getPreview:function(e){var a=this.options.breadcrumbs,t=this.dynamicOptions.breadcrumbs.archives.postTypes[e.name],s=t.useDefaultTemplate;return[s&&a.breadcrumbPrefix||!s&&t.showPrefixCrumb?a.breadcrumbPrefix:"",s&&a.homepageLink||!s&&t.showHomeCrumb?a.homepageLabel?a.homepageLabel:"Home":"",a.showBlogHome&&this.$aioseo.data.staticBlogPage&&"post"===e.name?"Blog Home":"",this.getArchiveTemplate(e)]},getArchiveTemplate:function(e){var a=this.dynamicOptions.breadcrumbs.archives.postTypes[e.name].useDefaultTemplate?this.$aioseo.breadcrumbs.defaultTemplates.archives.postTypes[e.name]:this.dynamicOptions.breadcrumbs.archives.postTypes[e.name].template;return a.replace(/#breadcrumb_archive_post_type_format/g,this.options.breadcrumbs.archiveFormat).replace(new RegExp("#breadcrumb_archive_post_type_name","g"),e.label)}},computed:Object(i["a"])(Object(i["a"])({},Object(n["e"])(["options","dynamicOptions"])),{},{archives:function(){return this.$aioseo.postData.archives}})},m=c,p=t("2877"),u=Object(p["a"])(m,s,r,!1,null,null,null);a["default"]=u.exports},c468:function(e,a,t){"use strict";t.r(a);var s=function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{staticClass:"preview-box"},[e.label?t("span",{staticClass:"label"},[e._v(" "+e._s(e.label)+": ")]):e._e(),e._l(this.getPreviewData(),(function(a,s){return[1<e.previewLength&&s>0&&s<e.previewLength?t("span",{key:s+"sep",staticClass:"aioseo-breadcrumb-separator",domProps:{innerHTML:e._s(e.options.breadcrumbs.separator)}}):e._e(),s<e.previewLength-1?t("span",{key:s+"crumb",class:{"aioseo-breadcrumb":!a.match(/aioseo-breadcrumb/),link:a!==e.options.breadcrumbs.breadcrumbPrefix&&!a.match(/<a /)},domProps:{innerHTML:e._s(a)}}):e._e(),s===e.previewLength-1?t("span",{key:s+"crumbLast",class:{last:!0,link:e.options.breadcrumbs.linkCurrentItem&&e.useDefaultTemplate&&!a.match(/<a /),noLink:!e.options.breadcrumbs.linkCurrentItem&&e.useDefaultTemplate,"aioseo-breadcrumb":!a.match(/aioseo-breadcrumb/)},domProps:{innerHTML:e._s(a)}}):e._e()]}))],2)},r=[],i=t("5530"),n=(t("d81d"),t("4de4"),t("ac1f"),t("5319"),t("fb6a"),t("2f62")),o={props:{previewData:{type:Array,default:null},useDefaultTemplate:{type:Boolean,default:!0},label:String},computed:Object(i["a"])(Object(i["a"])({},Object(n["e"])(["options"])),{},{previewLength:function(){return this.getPreviewData()?this.getPreviewData().length:0}}),methods:{getPreviewData:function(){var e=this,a=this.previewData.filter((function(e){return!!e})).map((function(a){return e.$tags.decodeHTMLEntities(a).replace(/#breadcrumb_separator/g,'<span class="aioseo-breadcrumb-separator">'+e.options.breadcrumbs.separator+"</span>").replace(/#breadcrumb_link/g,"Permalink")}));return this.useDefaultTemplate&&!this.options.breadcrumbs.showCurrentItem&&(a=a.slice(0,a.length-1)),a}}},c=o,m=t("2877"),p=Object(m["a"])(c,s,r,!1,null,null,null);a["default"]=p.exports}}]);