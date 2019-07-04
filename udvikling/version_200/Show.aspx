<%@ Page Title="" Language="vb" AutoEventWireup="false" MasterPageFile="~/Show.Master" CodeBehind="Show.aspx.vb" Inherits="MeOS_Live.Show1" %>

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
        var starttime = new Date(2019,06,30,15,36,59);
        
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
            klokken.innerHTML = starttime.toLocaleTimeString() + "<br>refresh: " +t;
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
    <span title="SETUP" style="position:fixed;top:0px;left:10px;z-index:20" id="loadtime">klokken</span>
    <ul id="horizontalmenu" class="underlinemenu">
      <!--
        @@KLASSELISTE@@
        <li><a href="#301" class="selected">Beg</a></li>
        <li><a href="#101">D10</a></li>
        <li><a href="#102">D12</a></li>
        <li><a href="#103" style="display: none;">D12B</a></li>
        <li><a href="#104" style="display: none;">D14</a></li>
      -->
      <li>
        <a href="#301" class="selected">Beg</a>
      </li>
      <li>
        <a href="#101">D10</a>
      </li>
      <li>
        <a href="#102">D12</a>
      </li>
      <li>
        <a href="#103" style="display: none;">D12B</a>
      </li>
      <li>
        <a href="#104" style="display: none;">D14</a>
      </li>
    </ul>
    <br style="clear:left" />
    <!-- Contents that Scroll Spy Menu 1 is spying on -->
    <div id="resultContainer">
      <!--div id="resultContainer">
        <!--
          @@RESUTATLISTER@@
          <div id="101">
            <h2>D10</h2>
            <table style="table-layout:fixed;" class="table-striped table-condensed table-bordered" width="100%">
              <thead>
                <tr>
                  <th style="text-align:right" width="6%">Pl.</th>
                  <th style="text-align:left" width="35%">Navn</th>
                  <th style="text-align:left" width="35%">Klub</th>
                  <th style="text-align:right" width="12%">Tid</th>
                  <th style="text-align:right" width="12%"></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="padding: 2px;" valign="top" align="right">1.</td>
                  <td style="padding: 2px;" valign="top" align="left">Camille Friis Scheby</td>
                  <td style="padding: 2px;" valign="top" align="left">OK Sor&oslash;</td>
                  <td style="padding: 2px;" valign="top" align="right">0:45:56</td>
                  <td style="padding: 2px;" valign="top" align="right"></td>
                </tr>
                <tr>
                  <td style="padding: 2px;" valign="top" align="right">2.</td>
                  <td style="padding: 2px;" valign="top" align="left">Franka S&oslash;ndergaard Pohl</td>
                  <td style="padding: 2px;" valign="top" align="left">OK Roskilde</td>
                  <td style="padding: 2px;" valign="top" align="right">0:52:20</td>
                  <td style="padding: 2px;" valign="top" align="right">+6:24</td>
                </tr>
              </tbody>
            </table>
          </div>
        -->
        <div id="301">
          <h2>Beg <asp:Label ID="ltest" runat="server" Text="..."></asp:Label></h2>            
          <table style="table-layout:fixed;" class="table-striped table-condensed table-bordered" width="100%">
            <thead>
              <tr>
                <th style="text-align:right" width="6%">Pl.</th>
                <th style="text-align:left" width="35%">Navn</th>
                <th style="text-align:left" width="35%">Klub</th>
                <th style="text-align:right" width="12%">Tid</th>
                <th style="text-align:right" width="12%"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">1.</td>
                <td style="padding: 2px;" valign="top" align="left">Jette Nygaard Jensen</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:49:09</td>
                <td style="padding: 2px;" valign="top" align="right"></td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">2.</td>
                <td style="padding: 2px;" valign="top" align="left">Ingrid Due Nygaard</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:49:38</td>
                <td style="padding: 2px;" valign="top" align="right">+0:29</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">3.</td>
                <td style="padding: 2px;" valign="top" align="left">Noah Andreas Holdensgaard J&oslash;rgensen</td>
                <td style="padding: 2px;" valign="top" align="left">OK Sor&oslash;</td>
                <td style="padding: 2px;" valign="top" align="right">0:50:43</td>
                <td style="padding: 2px;" valign="top" align="right">+1:34</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">4.</td>
                <td style="padding: 2px;" valign="top" align="left">Caroline Plenge R&oslash;nsov *L*</td>
                <td style="padding: 2px;" valign="top" align="left">OK &oslash;ST Birker&oslash;d</td>
                <td style="padding: 2px;" valign="top" align="right">0:50:52</td>
                <td style="padding: 2px;" valign="top" align="right">+1:43</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">5.</td>
                <td style="padding: 2px;" valign="top" align="left">Thomas Gerardo Hansen Petersen</td>
                <td style="padding: 2px;" valign="top" align="left">OK &oslash;ST Birker&oslash;d</td>
                <td style="padding: 2px;" valign="top" align="right">0:52:33</td>
                <td style="padding: 2px;" valign="top" align="right">+3:24</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">6.</td>
                <td style="padding: 2px;" valign="top" align="left">Alain GOikoetxea Lertxundi</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:53:07</td>
                <td style="padding: 2px;" valign="top" align="right">+3:58</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">7.</td>
                <td style="padding: 2px;" valign="top" align="left">Rune Cederberg</td>
                <td style="padding: 2px;" valign="top" align="left">Ballerup OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:53:35</td>
                <td style="padding: 2px;" valign="top" align="right">+4:26</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">8.</td>
                <td style="padding: 2px;" valign="top" align="left">Christoffer Anker Piepgrass</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:54:17</td>
                <td style="padding: 2px;" valign="top" align="right">+5:08</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">9.</td>
                <td style="padding: 2px;" valign="top" align="left">Anne M. Jensen</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:54:58</td>
                <td style="padding: 2px;" valign="top" align="right">+5:49</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">10.</td>
                <td style="padding: 2px;" valign="top" align="left">Eva Kirkeskov Grue</td>
                <td style="padding: 2px;" valign="top" align="left">OK &oslash;ST Birker&oslash;d</td>
                <td style="padding: 2px;" valign="top" align="right">0:55:11</td>
                <td style="padding: 2px;" valign="top" align="right">+6:02</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">11.</td>
                <td style="padding: 2px;" valign="top" align="left">Villads Elvang</td>
                <td style="padding: 2px;" valign="top" align="left">OK Sor&oslash;</td>
                <td style="padding: 2px;" valign="top" align="right">0:55:18</td>
                <td style="padding: 2px;" valign="top" align="right">+6:09</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">12.</td>
                <td style="padding: 2px;" valign="top" align="left">Andrea Hersh&oslash;j-Hjorth *L*</td>
                <td style="padding: 2px;" valign="top" align="left">Ballerup OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:55:26</td>
                <td style="padding: 2px;" valign="top" align="right">+6:17</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">13.</td>
                <td style="padding: 2px;" valign="top" align="left">Astrid Marie S&oslash;eberg Lindholm</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:55:30</td>
                <td style="padding: 2px;" valign="top" align="right">+6:21</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">14.</td>
                <td style="padding: 2px;" valign="top" align="left">Dagmar Marager</td>
                <td style="padding: 2px;" valign="top" align="left">Aller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:56:09</td>
                <td style="padding: 2px;" valign="top" align="right">+7:00</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">15.</td>
                <td style="padding: 2px;" valign="top" align="left">Birthe Nielsen</td>
                <td style="padding: 2px;" valign="top" align="left">OK Roskilde</td>
                <td style="padding: 2px;" valign="top" align="right">0:56:53</td>
                <td style="padding: 2px;" valign="top" align="right">+7:44</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">16.</td>
                <td style="padding: 2px;" valign="top" align="left">Linea Vedel Lange</td>
                <td style="padding: 2px;" valign="top" align="left">Farum OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:57:10</td>
                <td style="padding: 2px;" valign="top" align="right">+8:01</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">17.</td>
                <td style="padding: 2px;" valign="top" align="left">Carl B&oslash;dker Plambech</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:57:22</td>
                <td style="padding: 2px;" valign="top" align="right">+8:13</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">18.</td>
                <td style="padding: 2px;" valign="top" align="left">Esben Andersen *L*</td>
                <td style="padding: 2px;" valign="top" align="left">Ballerup OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:57:59</td>
                <td style="padding: 2px;" valign="top" align="right">+8:50</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">19.</td>
                <td style="padding: 2px;" valign="top" align="left">Anton Kupriyanov Hviid</td>
                <td style="padding: 2px;" valign="top" align="left">Farum OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:58:49</td>
                <td style="padding: 2px;" valign="top" align="right">+9:40</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">20.</td>
                <td style="padding: 2px;" valign="top" align="left">Asger Folke</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:58:59</td>
                <td style="padding: 2px;" valign="top" align="right">+9:50</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">21.</td>
                <td style="padding: 2px;" valign="top" align="left">Villads Lehn Tinghuus</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">1:02:45</td>
                <td style="padding: 2px;" valign="top" align="right">+13:36</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">-</td>
                <td style="padding: 2px;" valign="top" align="left">Amanda Hersh&oslash;j-Hjorth *L*</td>
                <td style="padding: 2px;" valign="top" align="left">Ballerup OK</td>
                <td style="padding: 2px;" valign="top" align="right">MP</td>
                <td style="padding: 2px;" valign="top" align="right"></td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">-</td>
                <td style="padding: 2px;" valign="top" align="left">Osvald B&oslash;dker Plambech</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">MP</td>
                <td style="padding: 2px;" valign="top" align="right"></td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">-</td>
                <td style="padding: 2px;" valign="top" align="left">Carmen Cecilie Christensen</td>
                <td style="padding: 2px;" valign="top" align="left">Holb&aelig;k OK</td>
                <td style="padding: 2px;" valign="top" align="right">MP</td>
                <td style="padding: 2px;" valign="top" align="right"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="101">
          <h2>D10</h2>
          <table style="table-layout:fixed;" class="table-striped table-condensed table-bordered" width="100%">
            <thead>
              <tr>
                <th style="text-align:right" width="6%">Pl.</th>
                <th style="text-align:left" width="35%">Navn</th>
                <th style="text-align:left" width="35%">Klub</th>
                <th style="text-align:right" width="12%">Tid</th>
                <th style="text-align:right" width="12%"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">1.</td>
                <td style="padding: 2px;" valign="top" align="left">Camille Friis Scheby</td>
                <td style="padding: 2px;" valign="top" align="left">OK Sor&oslash;</td>
                <td style="padding: 2px;" valign="top" align="right">0:45:56</td>
                <td style="padding: 2px;" valign="top" align="right"></td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">2.</td>
                <td style="padding: 2px;" valign="top" align="left">Franka S&oslash;ndergaard Pohl</td>
                <td style="padding: 2px;" valign="top" align="left">OK Roskilde</td>
                <td style="padding: 2px;" valign="top" align="right">0:52:20</td>
                <td style="padding: 2px;" valign="top" align="right">+6:24</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="102">
          <h2>D12</h2>
          <table style="table-layout:fixed;" class="table-striped table-condensed table-bordered" width="100%">
            <thead>
              <tr>
                <th style="text-align:right" width="6%">Pl.</th>
                <th style="text-align:left" width="35%">Navn</th>
                <th style="text-align:left" width="35%">Klub</th>
                <th style="text-align:right" width="12%">Tid</th>
                <th style="text-align:right" width="12%"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">1.</td>
                <td style="padding: 2px;" valign="top" align="left">Iris M&oslash;ller</td>
                <td style="padding: 2px;" valign="top" align="left">OK Sor&oslash;</td>
                <td style="padding: 2px;" valign="top" align="right">0:48:05</td>
                <td style="padding: 2px;" valign="top" align="right"></td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">2.</td>
                <td style="padding: 2px;" valign="top" align="left">Teresa S&oslash;ndergaard Pohl</td>
                <td style="padding: 2px;" valign="top" align="left">OK Roskilde</td>
                <td style="padding: 2px;" valign="top" align="right">0:50:07</td>
                <td style="padding: 2px;" valign="top" align="right">+2:02</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">3.</td>
                <td style="padding: 2px;" valign="top" align="left">Augusta Thorsen</td>
                <td style="padding: 2px;" valign="top" align="left">Farum OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:52:51</td>
                <td style="padding: 2px;" valign="top" align="right">+4:46</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">4.</td>
                <td style="padding: 2px;" valign="top" align="left">Selma Schytte Olsen</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:53:07</td>
                <td style="padding: 2px;" valign="top" align="right">+5:02</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">5.</td>
                <td style="padding: 2px;" valign="top" align="left">Signe Westen Rasmussen</td>
                <td style="padding: 2px;" valign="top" align="left">Aller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:53:39</td>
                <td style="padding: 2px;" valign="top" align="right">+5:34</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">6.</td>
                <td style="padding: 2px;" valign="top" align="left">Ella Kjems Falk *L*</td>
                <td style="padding: 2px;" valign="top" align="left">Ballerup OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:54:23</td>
                <td style="padding: 2px;" valign="top" align="right">+6:18</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">7.</td>
                <td style="padding: 2px;" valign="top" align="left">Alma Okkels Jensen</td>
                <td style="padding: 2px;" valign="top" align="left">OK Sor&oslash;</td>
                <td style="padding: 2px;" valign="top" align="right">0:55:05</td>
                <td style="padding: 2px;" valign="top" align="right">+7:00</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">8.</td>
                <td style="padding: 2px;" valign="top" align="left">Freja Brunn Fugmann *L*</td>
                <td style="padding: 2px;" valign="top" align="left">Ballerup OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:56:04</td>
                <td style="padding: 2px;" valign="top" align="right">+7:59</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">9.</td>
                <td style="padding: 2px;" valign="top" align="left">Kamma Aglund Andersen</td>
                <td style="padding: 2px;" valign="top" align="left">OK &oslash;ST Birker&oslash;d</td>
                <td style="padding: 2px;" valign="top" align="right">0:58:13</td>
                <td style="padding: 2px;" valign="top" align="right">+10:08</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="103">
          <h2>D12B</h2>
          <table style="table-layout:fixed;" class="table-striped table-condensed table-bordered" width="100%">
            <thead>
              <tr>
                <th style="text-align:right" width="6%">Pl.</th>
                <th style="text-align:left" width="35%">Navn</th>
                <th style="text-align:left" width="35%">Klub</th>
                <th style="text-align:right" width="12%">Tid</th>
                <th style="text-align:right" width="12%"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">1.</td>
                <td style="padding: 2px;" valign="top" align="left">Josefine Tenna Grue-S&oslash;rensen</td>
                <td style="padding: 2px;" valign="top" align="left">OK Roskilde</td>
                <td style="padding: 2px;" valign="top" align="right">0:51:32</td>
                <td style="padding: 2px;" valign="top" align="right"></td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">2.</td>
                <td style="padding: 2px;" valign="top" align="left">Szofi Presmann</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:54:25</td>
                <td style="padding: 2px;" valign="top" align="right">+2:53</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">3.</td>
                <td style="padding: 2px;" valign="top" align="left">Sofia Nielsen</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:55:22</td>
                <td style="padding: 2px;" valign="top" align="right">+3:50</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="104">
          <h2>D14</h2>
          <table style="table-layout:fixed;" class="table-striped table-condensed table-bordered" width="100%">
            <thead>
              <tr>
                <th style="text-align:right" width="6%">Pl.</th>
                <th style="text-align:left" width="35%">Navn</th>
                <th style="text-align:left" width="35%">Klub</th>
                <th style="text-align:right" width="12%">Tid</th>
                <th style="text-align:right" width="12%"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">1.</td>
                <td style="padding: 2px;" valign="top" align="left">Katrine Krogh Madsen</td>
                <td style="padding: 2px;" valign="top" align="left">OK Roskilde</td>
                <td style="padding: 2px;" valign="top" align="right">0:57:12</td>
                <td style="padding: 2px;" valign="top" align="right"></td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">2.</td>
                <td style="padding: 2px;" valign="top" align="left">Ellen K. Larsen</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">0:57:26</td>
                <td style="padding: 2px;" valign="top" align="right">+0:14</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">3.</td>
                <td style="padding: 2px;" valign="top" align="left">Clara Friis Scheby</td>
                <td style="padding: 2px;" valign="top" align="left">OK Sor&oslash;</td>
                <td style="padding: 2px;" valign="top" align="right">0:59:21</td>
                <td style="padding: 2px;" valign="top" align="right">+2:09</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">4.</td>
                <td style="padding: 2px;" valign="top" align="left">Hannah Gro Markussen</td>
                <td style="padding: 2px;" valign="top" align="left">Aller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">1:02:43</td>
                <td style="padding: 2px;" valign="top" align="right">+5:31</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">5.</td>
                <td style="padding: 2px;" valign="top" align="left">Frida Kärner Grooss</td>
                <td style="padding: 2px;" valign="top" align="left">Aller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">1:03:05</td>
                <td style="padding: 2px;" valign="top" align="right">+5:53</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">6.</td>
                <td style="padding: 2px;" valign="top" align="left">Agnes Due Nygaard</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">1:04:54</td>
                <td style="padding: 2px;" valign="top" align="right">+7:42</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">7.</td>
                <td style="padding: 2px;" valign="top" align="left">Frida Hagedorn</td>
                <td style="padding: 2px;" valign="top" align="left">S&oslash;ller&oslash;d OK</td>
                <td style="padding: 2px;" valign="top" align="right">1:05:03</td>
                <td style="padding: 2px;" valign="top" align="right">+7:51</td>
              </tr>
              <tr>
                <td style="padding: 2px;" valign="top" align="right">-</td>
                <td style="padding: 2px;" valign="top" align="left">Mathilde Elstorp</td>
                <td style="padding: 2px;" valign="top" align="left">Holb&aelig;k OK</td>
                <td style="padding: 2px;" valign="top" align="right">MP</td>
                <td style="padding: 2px;" valign="top" align="right"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </!--div>
    <!--/div //-->
    <br />
    <br />
</asp:Content>
