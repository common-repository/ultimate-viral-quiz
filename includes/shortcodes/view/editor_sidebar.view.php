<aside ng-controller="rightcolController" class="sidebar col-md-4 col-xs-12" role="complementary">
    
    <?php include_once 'editor_control.view.php'; ?>
    
    <section class="widget">
      <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php esc_html_e('Categories', 'ultimate-viral-quiz') ?></legend>
          <select ng-model="category" class="form-control">
            <option value="0"><?php esc_html_e('Choose one', 'ultimate-viral-quiz') ?></option>
            <?php foreach ($uvq_categories as $key => $cat) : ?>
                <option value="<?php esc_html_e($cat['cat_id']) ?>"><?php esc_html_e($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <br/>
      </fieldset>
      <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php esc_html_e('Tags', 'ultimate-viral-quiz') ?></legend>
          <ul class="list-buzztags list-unstyled list-inline list-tags">
            <li ng-repeat="tag in tags">
                <input type="checkbox" id="tag-{{ tag.tag_id }}" value="{{ tag.name }}" ng-model="tag.isCheck" ng-click="tagChecked($index)">
                <label for="tag-{{ tag.tag_id }}">
                  {{ tag.name }}
                </label>
            </li>
          </ul>
        <div class="clearfix"></div>
      </fieldset>
      <fieldset class="scheduler-border fuelux">
        <legend class="scheduler-border"><?php esc_html_e('Custom tags', 'ultimate-viral-quiz') ?></legend>
          <div class="pillbox" data-initialize="pillbox" id="myTags">
            <ul class="clearfix pill-group">
              <li class="pillbox-input-wrap btn-group">
                <input type="text" class="form-control dropdown-toggle pillbox-add-item" placeholder="<?php esc_attr_e('Add tag', 'ultimate-viral-quiz') ?>">
                <button type="button" class="dropdown-toggle sr-only">
                  <span class="caret"></span>
                </button>
                <ul class="suggest dropdown-menu" role="menu" data-toggle="dropdown" data-flip="auto"></ul>
              </li>
            </ul>
          </div>
          <div class="clearfix"></div>
          <br/>
      </fieldset>
      <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php esc_html_e('Permission', 'ultimate-viral-quiz') ?></legend>
          <select ng-model="permission" class="form-control">
            <option value="1"><?php esc_html_e('Public (recommended)', 'ultimate-viral-quiz') ?></option>
            <option value="2"><?php esc_html_e('Only Me', 'ultimate-viral-quiz') ?></option>
            <option value="3"><?php esc_html_e('Direct Link Only', 'ultimate-viral-quiz') ?></option>
          </select>
          <br/>
      </fieldset>
    </section>
</aside><!-- /.sidebar -->