<?php
include_once 'config.php';
include_once 'function.php';
$sql="select c.author,c.created,c.content from comments c ORDER BY c.created desc limit 6;";
$commentArr=query($sql);
$sqlRand="select p.id,p.title,p.views,p.feature from posts p;";
$randArr=[];
for ($i=0; $i <5 ; $i++) { 
  $rand=rand(0,count(query($sqlRand)));
  $randArr[]=query($sqlRand)[$rand];
}
?>
<div class="aside">
      <div class="widgets">
        <h4>搜索</h4>
        <div class="body search">
          <form>
            <input type="text" class="keys" placeholder="输入关键字">
            <input type="submit" class="btn" value="搜索">
          </form>
        </div>
      </div>
      <div class="widgets">
        <h4>随机推荐</h4>
        <ul class="body random">
          <?php
            foreach ($randArr as $key => $value) {?>
              <li>
            <a href="detail.php?postId=<?php echo $value['id'] ?>">
              <p class="title"><?php echo $value['title'] ?></p>
              <p class="reading">阅读(<?php echo $value['views'] ?>)</p>
              <div class="pic">
                <img src="<?php echo $value['feature'] ?>" alt="">
              </div>
            </a>
          </li>
            <?php }
          ?>
          <!-- <li>
            <a href="javascript:;">
              <p class="title">废灯泡的14种玩法 妹子见了都会心动</p>
              <p class="reading">阅读(819)</p>
              <div class="pic">
                <img src="static/uploads/widget_1.jpg" alt="">
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <p class="title">可爱卡通造型 iPhone 6防水手机套</p>
              <p class="reading">阅读(819)</p>
              <div class="pic">
                <img src="static/uploads/widget_2.jpg" alt="">
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <p class="title">变废为宝！将手机旧电池变为充电宝的Better</p>
              <p class="reading">阅读(819)</p>
              <div class="pic">
                <img src="static/uploads/widget_3.jpg" alt="">
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <p class="title">老外偷拍桂林芦笛岩洞 美如“地下彩虹”</p>
              <p class="reading">阅读(819)</p>
              <div class="pic">
                <img src="static/uploads/widget_4.jpg" alt="">
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <p class="title">doge神烦狗打底南瓜裤 就是如此魔性</p>
              <p class="reading">阅读(819)</p>
              <div class="pic">
                <img src="static/uploads/widget_5.jpg" alt="">
              </div>
            </a>
          </li> -->
        </ul>
      </div>
      <div class="widgets">
        <h4>最新评论</h4>
        <ul class="body discuz">
          <?php
          foreach ($commentArr as $key => $value) {?>
            <li>
              <a href="javascript:;">
                <div class="avatar">
                  <img src="static/uploads/avatar_1.jpg" alt="">
                </div>
                <div class="txt">
                  <p>
                    <span><?php echo $value['author'] ?></span><?php echo floor((time()-strtotime($value['created']))/60/60/24/30) ?>个月前(<?php echo date("m-d",strtotime($value['created'])) ?>)说:
                  </p>
                  <p class="comment-content"><?php echo $value['content'] ?></p>
                </div>
              </a>
              </li>
         <?php } ?>                       
        </ul>
      </div>
    </div>