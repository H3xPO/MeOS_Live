<%@ Page Title="MeOS Live" Language="VB" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Default.aspx.vb" Inherits="MeOS_Live._Default" %>

<asp:Content ID="BodyContent" ContentPlaceHolderID="MainContent" runat="server">
    <p><asp:Label ID="lError" runat="server" ></asp:Label></p>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="font-weight-bold">Existing event</div>
                <div class="container">
                    <div class="row">
                        <div class="col">                
                            Events<br /><asp:DropDownList ID="dlEvent" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled"></asp:DropDownList>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col">
                            Password<br /><asp:TextBox ID="tbEventPw" class="form-control" type="password" runat="server" Width="300px" ViewStateMode="Enabled"></asp:TextBox>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <br /><asp:Button ID="btOpen" class="btn btn-primary" runat="server" Text="Open" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">         
                <div class="font-weight-bold">New event</div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            Name<br><asp:TextBox ID="tbName" class="form-control" runat="server" MaxLength="128" Width="300px"></asp:TextBox> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Password<br /><asp:TextBox ID="tbPw" class="form-control" type="password" runat="server" MaxLength="24" Width="300px"></asp:TextBox>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <br /><asp:Button ID="btCreate" class="btn btn-primary" runat="server" Text="Create" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr />
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="font-weight-bold">Settings</div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            Number of displays<br /><asp:DropDownList ID="dlDisplays" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                <asp:ListItem Value="0">(Select displays)</asp:ListItem>
                                <asp:ListItem>1</asp:ListItem>
                                <asp:ListItem>2</asp:ListItem>
                                <asp:ListItem>3</asp:ListItem>
                                <asp:ListItem>4</asp:ListItem>
                                <asp:ListItem>5</asp:ListItem>
                                <asp:ListItem>6</asp:ListItem>
                                <asp:ListItem>7</asp:ListItem>
                                <asp:ListItem>8</asp:ListItem>
                                <asp:ListItem>9</asp:ListItem>
                            </asp:DropDownList>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="font-weight-bold"><br /></div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            MeOS Information Server<br /><asp:TextBox ID="tbMeOSSrv" class="form-control" runat="server" Width="300px"></asp:TextBox>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <br /><asp:Button ID="btServer" class="btn btn-primary" runat="server" Text="Connect" />&nbsp;<asp:Button ID="btReset" class="btn btn-primary" runat="server" Text="Reset" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align: center"><h3><asp:Label ID="lEventName" runat="server"></asp:Label>&nbsp;<asp:Image ID="imgServerStatus" runat="server" ImageUrl="~/Grafik/200px-Check_green_circle.png" Width="24" Visible="False" /></h3></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="font-weight-bold">News text</div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <asp:TextBox ID="tbNewsText" class="form-control" runat="server" Width="600px"></asp:TextBox>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="font-weight-bold"><br /></div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <asp:RadioButtonList ID="rbNewsColor" runat="server" RepeatDirection="Horizontal">
                                <asp:ListItem Selected="True">Blue/White</asp:ListItem>
                                <asp:ListItem>Red/White</asp:ListItem>
                                <asp:ListItem>Yellow/Black</asp:ListItem>
                            </asp:RadioButtonList>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="font-weight-bold">Display configuration</div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            Configuration for display #<br /><asp:DropDownList ID="dlDisplay" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled" AutoPostBack="True"></asp:DropDownList>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <br /><asp:Button ID="btShow" class="btn btn-primary" runat="server" Text="Show display" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="font-weight-bold"><br /></div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            Number of colums on display <asp:Label ID="lSelectedDisplay" runat="server" Text=""></asp:Label><br /><asp:DropDownList ID="dlColums" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled" AutoPostBack="True">
                                <asp:ListItem Value="0">(Select columns)</asp:ListItem>
                                <asp:ListItem>1</asp:ListItem>
                                <asp:ListItem>2</asp:ListItem>
                                <asp:ListItem>3</asp:ListItem>
                            </asp:DropDownList>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <br /><asp:Button ID="btDisplaySave" class="btn btn-primary" runat="server" Text="Save display" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr />
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="font-weight-bold">Colum configuration</div>

                    <asp:MultiView ID="ColumView" runat="server">
                        <asp:View ID="ColumView1" runat="server">                                    
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        Content<br />
                                        <asp:DropDownList ID="ColumnView1_dlContent1" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select content)</asp:ListItem>
                                            <asp:ListItem Value="1">Resultlist</asp:ListItem>
                                            <asp:ListItem Value="2">Startlist</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Size<br />
                                        <asp:DropDownList ID="ColumnView1_dlSize1" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select size)</asp:ListItem>
                                            <asp:ListItem Value="25">25%</asp:ListItem>
                                            <asp:ListItem Value="33">33%</asp:ListItem>
                                            <asp:ListItem Value="50">50%</asp:ListItem>
                                            <asp:ListItem Value="66">66%</asp:ListItem>
                                            <asp:ListItem Value="75">75%</asp:ListItem>
                                            <asp:ListItem Value="100">100%</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Classes<br />
                                        <asp:CheckBoxList ID="ColumView1_cbClasses1" style="width: 300px;" runat="server" Width="300px" ViewStateMode="Enabled">
                                        </asp:CheckBoxList>
                                    </div>
                                </div>
                            </div>
                        </asp:View>
                        <asp:View ID="ColumView2" runat="server">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        Content<br />
                                        <asp:DropDownList ID="ColumnView2_dlContent1" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select content)</asp:ListItem>
                                            <asp:ListItem Value="1">Resultlist</asp:ListItem>
                                            <asp:ListItem Value="2">Startlist</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                    <div class="col">
                                        Content<br />
                                        <asp:DropDownList ID="ColumnView2_dlContent2" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select content)</asp:ListItem>
                                            <asp:ListItem Value="1">Resultlist</asp:ListItem>
                                            <asp:ListItem Value="2">Startlist</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Size<br />
                                        <asp:DropDownList ID="ColumnView2_dlSize1" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select size)</asp:ListItem>
                                            <asp:ListItem Value="25">25%</asp:ListItem>
                                            <asp:ListItem Value="33">33%</asp:ListItem>
                                            <asp:ListItem Value="50">50%</asp:ListItem>
                                            <asp:ListItem Value="66">66%</asp:ListItem>
                                            <asp:ListItem Value="75">75%</asp:ListItem>
                                            <asp:ListItem Value="100">100%</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                    <div class="col">
                                        Size<br />
                                        <asp:DropDownList ID="ColumnView2_dlSize2" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select size)</asp:ListItem>
                                            <asp:ListItem Value="25">25%</asp:ListItem>
                                            <asp:ListItem Value="33">33%</asp:ListItem>
                                            <asp:ListItem Value="50">50%</asp:ListItem>
                                            <asp:ListItem Value="66">66%</asp:ListItem>
                                            <asp:ListItem Value="75">75%</asp:ListItem>
                                            <asp:ListItem Value="100">100%</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Classes<br />
                                        <asp:CheckBoxList ID="ColumView2_cbClasses1" style="width: 300px;" runat="server" Width="300px" ViewStateMode="Enabled">
                                        </asp:CheckBoxList>
                                    </div>
                                    <div class="col">
                                        Classes<br />
                                        <asp:CheckBoxList ID="ColumView2_cbClasses2" style="width: 300px;" runat="server" Width="300px" ViewStateMode="Enabled">
                                        </asp:CheckBoxList>
                                    </div>
                                </div>
                            </div>
                        </asp:View>
                        <asp:View ID="ColumView3" runat="server">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        Content<br />
                                        <asp:DropDownList ID="ColumnView3_dlContent1" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select content)</asp:ListItem>
                                            <asp:ListItem Value="1">Resultlist</asp:ListItem>
                                            <asp:ListItem Value="2">Startlist</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                    <div class="col">
                                        Content<br />
                                        <asp:DropDownList ID="ColumnView3_dlContent2" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select content)</asp:ListItem>
                                            <asp:ListItem Value="1">Resultlist</asp:ListItem>
                                            <asp:ListItem Value="2">Startlist</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                    <div class="col">
                                        Content<br />
                                        <asp:DropDownList ID="ColumnView3_dlContent3" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select content)</asp:ListItem>
                                            <asp:ListItem Value="1">Resultlist</asp:ListItem>
                                            <asp:ListItem Value="2">Startlist</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Size<br />
                                        <asp:DropDownList ID="ColumnView3_dlSize1" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select size)</asp:ListItem>
                                            <asp:ListItem Value="25">25%</asp:ListItem>
                                            <asp:ListItem Value="33">33%</asp:ListItem>
                                            <asp:ListItem Value="50">50%</asp:ListItem>
                                            <asp:ListItem Value="66">66%</asp:ListItem>
                                            <asp:ListItem Value="75">75%</asp:ListItem>
                                            <asp:ListItem Value="100">100%</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                    <div class="col">
                                        Size<br />
                                        <asp:DropDownList ID="ColumnView3_dlSize2" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select size)</asp:ListItem>
                                            <asp:ListItem Value="25">25%</asp:ListItem>
                                            <asp:ListItem Value="33">33%</asp:ListItem>
                                            <asp:ListItem Value="50">50%</asp:ListItem>
                                            <asp:ListItem Value="66">66%</asp:ListItem>
                                            <asp:ListItem Value="75">75%</asp:ListItem>
                                            <asp:ListItem Value="100">100%</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                    <div class="col">
                                        Size<br />
                                        <asp:DropDownList ID="ColumnView3_dlSize3" class="form-control" runat="server" Width="300px" ViewStateMode="Enabled">
                                            <asp:ListItem Value="0">(Select size)</asp:ListItem>
                                            <asp:ListItem Value="25">25%</asp:ListItem>
                                            <asp:ListItem Value="33">33%</asp:ListItem>
                                            <asp:ListItem Value="50">50%</asp:ListItem>
                                            <asp:ListItem Value="66">66%</asp:ListItem>
                                            <asp:ListItem Value="75">75%</asp:ListItem>
                                            <asp:ListItem Value="100">100%</asp:ListItem>
                                        </asp:DropDownList>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Classes<br />
                                        <asp:CheckBoxList ID="ColumView3_cbClasses1" style="width: 300px;" runat="server" Width="300px" ViewStateMode="Enabled">
                                        </asp:CheckBoxList>
                                    </div>
                                    <div class="col">
                                        Classes<br />
                                        <asp:CheckBoxList ID="ColumView3_cbClasses2" style="width: 300px;" runat="server" Width="300px" ViewStateMode="Enabled">
                                        </asp:CheckBoxList>
                                    </div>
                                    <div class="col">
                                        Classes<br />
                                        <asp:CheckBoxList ID="ColumView3_cbClasses3" style="width: 300px;" runat="server" Width="300px" ViewStateMode="Enabled">
                                        </asp:CheckBoxList>
                                    </div>
                                </div>
                            </div>
                        </asp:View>
                    </asp:MultiView>
            </div>
        </div>
    </div>    
    
</asp:Content>