<%@ Page Title="MeOS Live" Language="VB" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Default.aspx.vb" Inherits="MeOS_Live._Default" %>

<asp:Content ID="BodyContent" ContentPlaceHolderID="MainContent" runat="server">
    <asp:Label ID="lError" runat="server" ForeColor="Red" ></asp:Label><br />
    <p>MeOS Informationsserver <asp:TextBox ID="tbMeOSSrv" runat="server" Width="500px"></asp:TextBox>&nbsp;<asp:Label ID="lServerStatus" runat="server" ></asp:Label><br /></p>
    <p>Antal displays <asp:TextBox ID="tbDisplays" runat="server" Width="50px"></asp:TextBox></p>
    <p>
    <asp:Button ID="btServer" class="btn btn-primary" runat="server" Text="Opdater/kontroller server" />
    <asp:Button ID="btReset" class="btn btn-primary" runat="server" Text="Nulstil" />
    </p>
    <p>
    <asp:Button ID="btCompetition" class="btn btn-primary" runat="server" Text="Løb" Enabled="False" />
    <asp:Button ID="btClass" class="btn btn-primary" runat="server" Text="Klasser" Enabled="False" />
    <asp:Button ID="btControl" class="btn btn-primary" runat="server" Text="Poster" Enabled="False" />
    <asp:Button ID="btCompetitor" class="btn btn-primary" runat="server" Text="Deltagere" Enabled="False" />
    <asp:Button ID="btOrganization" class="btn btn-primary" runat="server" Text="Klubber" Enabled="False" />
    <asp:Button ID="btResult" class="btn btn-primary" runat="server" Text="Resultat" Enabled="False" />
    </p>
    <asp:Label ID="lResultat" runat="server"></asp:Label>
</asp:Content>