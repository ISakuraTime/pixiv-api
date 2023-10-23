# Pixiv Api
这个是依赖pixiv web api实现的

---
# 文档
> 以下文档默认php部署到web路径根目录
## Imgae-url图片

**必选参数**: `url`图片地址

**接口地址**: `image-url.php`

**调用例子**: `/image-url.php?url=https://i.pximg.net/img-original/img/2019/06/14/00/00/02/75209748_p0.jpg`



## Imgae-id图片

**必选参数**: `id`图片地址

**接口地址**: `/image-id.php`

**调用例子**: `/image-id.php?id=75209748`



## Artworks 作品

**必选参数**: `workid`作品ID

**接口地址**: `/artworks.php`

**调用例子**: `/artworks.php?id=112712667`



## Rank排行榜

**可选参数**: `content`

> 排行榜内容:  
>
> 综合(默认)无参数
>
> `illust`插画
>
> `ugoira`动图
>
> `manga`漫画

**可选参数**: `mode`

>  排行榜模式:  
>
> `daily`今日排行榜
>
> `weekly`本周排行榜
>
> `monthly`本月排行榜 **(动图内容无效)**
>
> `rookie`新人排行榜 **(动图内容无效)**
>
> `original`原创排行榜 **(仅在综合内容)** **(动图内容无效)**
>
> `daily_ai`AI生成排行榜**(仅在综合内容)** **(动图内容无效)**
>
> `male`受男性欢迎排行榜**(仅在综合内容)** **(动图内容无效)**
>
> `female`受女性欢迎排行榜**(仅在综合内容)** **(动图内容无效)**

可选参数: `date`

> 日期格式: `YYYYMMdd`
>
> 例子: `20231021`

**可选参数**: `?mode=daily&content=illust`

**接口地址**: `/rank.php`

**调用例子**: `/rank.php?mode=daily&content=ugoira&date=20231021`



## user-profile-all用户所有作品

**必选参数**: `userid`**用户ID**

**接口地址**: `/user-profile-all.php`

**调用例子**: `/user-profile-all.php?userid=211515`



## user-profile-illusts用户作品预览

**必选参数**：`userid`用户`id`

**必选参数**: `ids`用户作品`id`使用`,`分割

**可选参数**: `work_category`

> 作品类别
>
> `illust`默认
>
> `manga`漫画

**可选参数**: `lang` 语言, 默认`zh`

**可选参数**: `version`版本?目前不清楚

**接口地址**: `/user-profile-illusts.php`

**调用例子**: `/user-profile-illusts.php?userid=211515&ids=112686358,111334260`



## user-profile-tags-illusts用户作品tag

**必选参数**: `ids`用户作品`id`使用`,`分割

**可选参数**: `lang` 语言, 默认`zh`

可选参数: `version`版本?目前不清楚

**接口地址**: `/user-profile-tags-illusts.php`

**调用例子**: `/user-profile-tags-illusts.php?ids=1098570,1141516,1149594,1199900`



## tag-all排行榜Tag

**可选参数**: `zh`

**接口地址**: `/tag-all.php`

**调用例子**: `/tag-all.php`



## tag-front-cover Tag简介信息

**可选参数**: `lang` 语言, 默认`zh`

**可选参数**: `version`版本?目前不清楚

**接口地址**: `/tag-front-cover.php`

**调用例子**: `/tag-front-cover.php`



## tag-illustrations Tag插画

**必选参数**: `tagname` tag名称

**可选参数**: `order` 排序

> `date`按旧排序
>
> `date_d` 最新排序

**可选参数**: `mode`未知参数, 默认`all`

**可选参数**: `p`分页参数, 默认`1`

**可选参数**: `s_mode`未知参数, 默认`s_tag_full`

**可选参数**: `type`tag排行榜类型

>  `s_tag_full`插画(默认)
>
> `manga` 漫画

**接口地址**: `/tag-illustrations.php`

**调用例子**: `/tag-illustrations.php`