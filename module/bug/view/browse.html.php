<?php
/**
 * The browse view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: browse.html.php 5102 2013-07-12 00:59:54Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/treeview.html.php';
include '../../common/view/colorize.html.php';
include '../../common/view/dropmenu.html.php';
js::set('browseType', $browseType);
js::set('moduleID', $moduleID);
js::set('customed', $customed);
?>

<div id='featurebar'>
  <div class='f-left'>
    <?php
    echo "<span id='allTab'>"           . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage=200"), $lang->bug->allBugs) . "</span>";
    echo "<span id='assigntomeTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=assignToMe&param=0"),    $lang->bug->assignToMe)    . "</span>";
    echo "<span id='openedbymeTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=openedByMe&param=0"),    $lang->bug->openedByMe)    . "</span>";
    echo "<span id='resolvedbymeTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=resolvedByMe&param=0"),  $lang->bug->resolvedByMe)  . "</span>";
    echo "<span id='assigntonullTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=assignToNull&param=0"),  $lang->bug->assignToNull)  . "</span>";
    echo "<span id='unresolvedTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=unResolved&param=0"),    $lang->bug->unResolved)    . "</span>";
    echo "<span id='unclosedTab'>"      . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=unclosed&param=0"),      $lang->bug->unclosed)      . "</span>";
    echo "<span id='longlifebugsTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=longLifeBugs&param=0"),  $lang->bug->longLifeBugs)  . "</span>";
    echo "<span id='postponedbugsTab'>" . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=postponedBugs&param=0"), $lang->bug->postponedBugs) . "</span>";
    echo "<span id='needconfirmTab'>"   . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=needconfirm&param=0"), $lang->bug->needConfirm) . "</span>";
    echo "<span id='bysearchTab'><a href='#' class='link-icon'><i class='icon-search icon'></i>&nbsp;{$lang->bug->byQuery}</a></span> ";
    ?>
  </div>
  <div class='f-right'>
    <?php

    echo '<span class="link-button dropButton">';
    echo html::a("#", "<i class='icon-upload-alt'></i> " . $lang->export, '', "id='exportAction' onclick=toggleSubMenu(this.id,'bottom',0) title='{$lang->export}'");
    echo '</span>';

    common::printIcon('bug', 'report', "productID=$productID&browseType=$browseType&moduleID=$moduleID");
    common::printIcon('bug', 'customFields', '', '', 'button', 'icon-wrench');
    common::printIcon('bug', 'batchCreate', "productID=$productID&projectID=0&moduleID=$moduleID");
    common::printIcon('bug', 'create', "productID=$productID&extra=moduleID=$moduleID");
    ?>
  </div>
</div>
<div id='exportActionMenu' class='listMenu hidden'>
  <ul>
  <?php 
  $misc = common::hasPriv('bug', 'export') ? "class='export'" : "class=disabled";
  $link = common::hasPriv('bug', 'export') ?  $this->createLink('bug', 'export', "productID=$productID&orderBy=$orderBy") : '#';
  echo "<li>" . html::a($link, $lang->bug->export, '', $misc) . "</li>";
  ?>
  </ul>
</div>

<div id='querybox' class='<?php if($browseType !='bysearch') echo 'hidden';?>'></div>

<?php 
if($customed)
{
    include './browse.custom.html.php'; 
    include '../../common/view/footer.lite.html.php';
    exit;
}
?>

<div class='treeSlider' id='bugTree'><span>&nbsp;</span></div>
<form method='post'>
  <table class='cont-lt1'>
    <tr valign='top'>
      <td class='side' id='treebox'>
        <div class='box-title'><?php echo $productName;?></div>
        <div class='box-content'>
          <?php echo $moduleTree;?>
          <div class='a-right'>
            <?php common::printLink('tree', 'browse', "productID=$productID&view=bug", $lang->tree->manage);?>
            <?php common::printLink('tree', 'fix',    "root=$productID&type=bug", $lang->tree->fix, 'hiddenwin');?>
          </div>
        </div>
      </td>
      <td class='divider'></td>
      <td>
        <table class='table-1 fixed colored tablesorter datatable' id='bugList'>
          <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
          <thead>
          <tr class='colhead'>
            <th class='w-id'>       <?php common::printOrderLink('id',          $orderBy, $vars, $lang->idAB);?></th>
            <th class='w-severity'> <?php common::printOrderLink('severity',    $orderBy, $vars, $lang->bug->severityAB);?></th>
            <th class='w-pri'>      <?php common::printOrderLink('pri',         $orderBy, $vars, $lang->priAB);?></th>

            <th>                    <?php common::printOrderLink('title',       $orderBy, $vars, $lang->bug->title);?></th>

            <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
            <th class='w-80px'><?php common::printOrderLink('status',           $orderBy, $vars, $lang->bug->statusAB);?></th>
            <?php endif;?>

            <?php if($browseType == 'needconfirm'):?>
            <th class='w-200px'><?php common::printOrderLink('story',           $orderBy, $vars, $lang->bug->story);?></th>
            <th class='w-50px'><?php echo $lang->actions;?></th>
            <?php else:?>
            <th class='w-user'><?php common::printOrderLink('openedBy',         $orderBy, $vars, $lang->openedByAB);?></th>

            <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
            <th class='w-date'><?php common::printOrderLink('openedDate',       $orderBy, $vars, $lang->bug->openedDateAB);?></th>
            <?php endif;?>

            <th class='w-user'><?php common::printOrderLink('assignedTo',       $orderBy, $vars, $lang->assignedToAB);?></th>
            <th class='w-user'><?php common::printOrderLink('resolvedBy',       $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
            <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>

            <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
            <th class='w-date'><?php common::printOrderLink('resolvedDate',     $orderBy, $vars, $lang->bug->resolvedDateAB);?></th>
            <?php endif;?>

            <th class='w-140px {sorter:false}'><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
          </thead>
          <tbody>
          <?php foreach($bugs as $bug):?>
          <?php $bugLink = inlink('view', "bugID=$bug->id");?>
          <tr class='a-center'>
            <td class='<?php echo $bug->status;?>' style="font-weight:bold">
              <input type='checkbox' name='bugIDList[]'  value='<?php echo $bug->id;?>'/> 
              <?php echo html::a($bugLink, sprintf('%03d', $bug->id));?>
            </td>
            <td><span class='<?php echo 'severity' . $bug->severity;?>'><?php echo $bug->severity;?></span></td>
            <td><span class='<?php echo 'pri' . $lang->bug->priList[$bug->pri];?>'><?php echo $lang->bug->priList[$bug->pri];?></span></td>

            <?php $class = 'confirm' . $bug->confirmed;?>
            <td class='a-left' title="<?php echo $bug->title?>"><?php echo "<span class='$class'>[{$lang->bug->confirmedList[$bug->confirmed]}] </span>" . html::a($bugLink, $bug->title);?></td>

            <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
            <td><?php echo $lang->bug->statusList[$bug->status];?></td>
            <?php endif;?>

            <?php if($browseType == 'needconfirm'):?>
            <td class='a-left' title="<?php echo $bug->storyTitle?>"><?php echo html::a($this->createLink('story', 'view', "stoyID=$bug->story"), $bug->storyTitle, '_blank');?></td>
            <td><?php $lang->bug->confirmStoryChange = $lang->confirm; common::printIcon('bug', 'confirmStoryChange', "bugID=$bug->id", '', 'list', '', 'hiddenwin')?></td>
            <?php else:?>
            <td><?php echo zget($users, $bug->openedBy, $bug->openedBy);?></td>

            <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
            <td><?php echo substr($bug->openedDate, 5, 11)?></td>
            <?php endif;?>

            <td <?php if($bug->assignedTo == $this->app->user->account) echo 'class="red"';?>><?php echo zget($users, $bug->assignedTo, $bug->assignedTo);?></td>
            <td><?php echo zget($users, $bug->resolvedBy, $bug->resolvedBy)?></td>
            <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>

            <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
            <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
            <?php endif;?>

            <td class='a-right'>
              <?php
              $params = "bugID=$bug->id";
              common::printIcon('bug', 'confirmBug', $params, $bug, 'list', '', '', 'iframe', true);
              common::printIcon('bug', 'assignTo',   $params, '',   'list', '', '', 'iframe', true);
              common::printIcon('bug', 'resolve',    $params, $bug, 'list', '', '', 'iframe', true);
              common::printIcon('bug', 'close',      $params, $bug, 'list', '', '', 'iframe', true);
              common::printIcon('bug', 'edit',       $params, $bug, 'list');
              common::printIcon('bug', 'create',     "product=$bug->product&extra=bugID=$bug->id", $bug, 'list', 'copy');
              ?>
            </td>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <?php
              $columns = $this->cookie->windowWidth >= $this->config->wideSize ? 12 : 9;
              if($browseType == 'needconfirm') $columns = $this->cookie->windowWidth >= $this->config->wideSize ? 7 : 6; 
              ?>
              <td colspan='<?php echo $columns;?>'>
                <?php if(!empty($bugs)):?>
                <div class='f-left'>
                  <?php 
                  echo "<div class='groupButton'>";
                  echo html::selectAll() . html::selectReverse();
                  echo "</div>";

                  $actionLink = $this->createLink('bug', 'batchEdit', "productID=$productID");
                  $misc       = common::hasPriv('bug', 'batchEdit') ? "onclick=setFormAction('$actionLink')" : "disabled='disabled'";
                  echo "<div class='groupButton dropButton'>";
                  echo html::commonButton($lang->edit, $misc);
                  echo "<button id='moreAction' type='button' onclick=\"toggleSubMenu(this.id, 'top', 0)\"><span class='caret'></span></button>";
                  echo "</div>";
                 ?>
                </div>
                <?php endif?>
                <div class='f-right'><?php $pager->show();?></div>
              </td>
            </tr>
          </tfoot>
        </table>
      </td>
    </tr>
  </table>  
</form>

<div id='moreActionMenu' class='listMenu hidden'>
  <ul>
  <?php 
  $class = "class='disabled'";

  $actionLink = $this->createLink('bug', 'batchConfirm');
  $misc = common::hasPriv('bug', 'batchConfirm') ? "onclick=setFormAction('$actionLink','hiddenwin')" : "class='disabled'";
  echo "<li>" . html::a('#', $lang->bug->confirmBug, '', $misc) . "</li>";

  $misc = common::hasPriv('bug', 'batchResolve') ? "onmouseover='toggleSubMenu(this.id)' onmouseout='toggleSubMenu(this.id)' id='resolveItem'" : $class;
  echo "<li>" . html::a('#', $lang->bug->resolve,  '', $misc) . "</li>";
  ?>
  </ul>
</div>

<div id='resolveItemMenu' class='hidden listMenu'>
  <ul>
  <?php
  unset($lang->bug->resolutionList['']);
  unset($lang->bug->resolutionList['duplicate']);
  foreach($lang->bug->resolutionList as $key => $resolution)
  {
      $actionLink = $this->createLink('bug', 'batchResolve', "resolution=$key");
      echo "<li>";
      if($key == 'fixed')
      {
          echo html::a('#', $resolution, '', "onmouseover=toggleSubMenu(this.id,'right',2) id='fixedItem'");
      }
      else
      {
          echo html::a('#', $resolution, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
      }
      echo "</li>";
  }
  ?>
  </ul>
</div>

<div id='fixedItemMenu' class='hidden listMenu'>
  <ul>
  <?php
  unset($builds['']);
  foreach($builds as $key => $build)
  {
      $actionLink = $this->createLink('bug', 'batchResolve', "resolution=fixed&resolvedBuild=$key");
      echo "<li>";
      echo html::a('#', $build, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
      echo "</li>";
  }
  ?>
  </ul>
</div>

<?php include '../../common/view/footer.html.php';?>
