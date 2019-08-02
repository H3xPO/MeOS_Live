<%@ Page Title="" Language="vb" AutoEventWireup="false" MasterPageFile="~/Show.Master" CodeBehind="Show_1.aspx.vb" Inherits="MeOS_Live.Show_1" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
    <meta http-equiv="Content-Type" content="text/html" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="/ShowFiles/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/ShowFiles/js/bootstrap.min.js" rel="stylesheet" />
    <script type="text/javascript" src="/ShowFiles/js/jquery-1.11.0.js"></script>
    <link href="/ShowFiles/css/ddscrollspy.css" rel="stylesheet" />
    <script type="text/javascript" src="/ShowFiles/js/ddscrollspy.js">
    /*
    * DD ScrollSpy Menu Script (c) Dynamic Drive (www.dynamicdrive.com)
    * Last updated: Dec 29th, 12&#39;
    * Visit http://www.dynamicdrive.com/ for this script and 100s more.
    */
    </script>

    <script>
        var pixelspersecond = 32; //16
        var numberofscrolls = 1;
        var dur;
        var minreloadinterval = 60000; // millisecs
        var topbottomdelay = 10000; //millisecs
        var scrollduration; // millisecs
        var headerOffset;

        var ts_y = "<%=DateTime.Now.Year%>";
        var ts_m = "<%=DateTime.Now.Month%>";
        var ts_d = "<%=DateTime.Now.Day%>";
        var ts_hh = "<%=DateTime.Now.Hour%>";
        var ts_mm = "<%=DateTime.Now.Minute%>";
        var ts_ss = "<%=DateTime.Now.Second%>";
        var starttime = new Date(ts_y, ts_m, ts_d, ts_hh, ts_mm, ts_ss);
        if (ts_hh.length == 1) { ts_hh = "0" + ts_hh; }
        if (ts_mm.length == 1) { ts_mm = "0" + ts_mm; }
        if (ts_ss.length == 1) { ts_ss = "0" + ts_ss; }

        elapsetime = 0;

        jQuery(function($){ // on document load

            $('#horizontalmenu').ddscrollSpy({ // initialize first demo
                scrolltopoffset: -50
            })

            klokken = document.getElementById("loadtime");
            h = window.innerHeight;
            headerOffset=document.body.offsetHeight - h;
            scrollduration =1000*headerOffset/pixelspersecond;
            dur = (numberofscrolls*(scrollduration + 2*topbottomdelay + 1000 + 500)).toFixed(0);
            if (dur < minreloadinterval)
            {
                dur = minreloadinterval;
            }

            setTimeout(setKlokken);
            startAnimation();
        })

        function setKlokken()
        {
            elapsetime += 1000; // one second since last call
            t = ((dur - elapsetime)/1000).toFixed(0);
            if (t < 0) {t=0;}
            //klokken.innerHTML = starttime.toLocaleTimeString() + "<br>refresh: " + t;
            klokken.innerHTML = ts_hh+":"+ts_mm+":"+ts_ss + " (" + t + ")";
            setTimeout(setKlokken, 1000);
        }

        function startAnimation()
        {
            //n = (new Date()).getTime();
            if (elapsetime >= (dur - topbottomdelay))
            {
                window.scrollTo(0,0);
                location.reload();
            }
            else
            {
                $('body').delay(topbottomdelay).animate(  {scrollTop: headerOffset},
                    {
                        duration: scrollduration,
                        easing: "linear" ,
                        done : function() { $('body').delay(topbottomdelay).animate({scrollTop:0}, {duration:1000, easing: "linear", done : function(){ setTimeout(startAnimation, 500);}}); }
                    }
                );
            }
        }
   
    </script>
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="main" runat="server">
    <style>
        body {
            overflow: hidden;
        }
    </style>
    <asp:ImageButton style="position:fixed;top:2px;left:2px;z-index:20" ID="btSetup" runat="server" ImageUrl="~/Grafik/setup-512.png" ImageAlign="Middle" Height="32" Width="32" />
    <span style="position:fixed;top:0px;left:40px;z-index:20" id="loadtime">klokken</span>
    <span style="position:fixed;top:18px;left:40px;z-index:20"><asp:Label ID="lDisplayID" runat="server" Text=""></asp:Label></span>
    <ul id="horizontalmenu" class="underlinemenu">
        <asp:Label ID="lClassList" runat="server" Text=""></asp:Label>
    </ul>
    <br style="clear:left" />
    <!-- Contents that Scroll Spy Menu 1 is spying on -->
    <div id="resultContainer">
        <asp:Label ID="lResultList" runat="server" Text=""></asp:Label>
    </div>
    <br />
    <br />
</asp:Content>
