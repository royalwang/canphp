  <div class="admin_title">
   <h2>导入应用</h2>
   <a class="right" href="javascript:history.back(-1)">返回上一页</a>
   </div>
   
  <form enctype="multipart/form-data" method="post" action="{url('index/import')}">
    <div class="form_box">
        <table>
            <tr>
              <th>应用安装包：</th>
              <td><input class="inputfile w400" type="file" name="file" id="file" /></td>
            </tr>
        </table>
      </div>
      <div class="btn">
      <input value="yes" type="hidden" name="do" />
      <input type="submit" value="确 定" class="button">
      <input type="reset" value="重 置" class="button">
      </div>
  </form>