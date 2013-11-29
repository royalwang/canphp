  <div class="admin_title">
   <h2>创建应用</h2>
   <a class="right" href="javascript:history.back(-1)">返回上一页</a>
   </div>
  <form enctype="multipart/form-data" method="post" action="{url('index/create')}">
     <div class="form_box">
        <table>
            <tr>
              <th>应用ID：</th>
              <td><input class="input w400" type="text" name="app_id" id="app_id" />(必须为全小写字母)</td>
            </tr>
            <tr>
              <th>应用名称：</th>
              <td><input class="input w400" type="text" name="app_name" id="app_name" /></td>
            </tr>
        </table>
      </div>
   <div class="btn">
   <input type="submit" name="dosubmit" value="创 建" class="button">
   </div>
  </form>