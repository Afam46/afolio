<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Afolio Message</title>
</head>
<body style="
    margin:0;
    padding:0;
    background:#020617;
    font-family:Arial,sans-serif;
">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 20px;">

                <table width="600" cellpadding="0" cellspacing="0" style="
                    background:#0f172a;
                    border-radius:24px;
                    overflow:hidden;
                    border:1px solid rgba(255,255,255,0.08);
                ">
                    <tr>
                        <td style="
                            padding:32px;
                            border-bottom:1px solid rgba(255,255,255,0.08);
                            text-align:center;
                        ">

                            <h1 style="
                                margin:0;
                                color:#22d3ee;
                                font-size:32px;
                                letter-spacing:2px;
                            ">
                                Afolio
                            </h1>

                            <p style="
                                margin-top:10px;
                                color:#94a3b8;
                                font-size:14px;
                            ">
                                New contact form message
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">

                            <div style="
                                background:#020617;
                                border-radius:18px;
                                padding:24px;
                                border:1px solid rgba(255,255,255,0.06);
                            ">

                                <p style="color:#22d3ee; margin:0 0 10px;">
                                    Name
                                </p>

                                <p style="color:white; margin:0 0 24px;">
                                    {{ $data['name'] }}
                                </p>

                                <p style="color:#22d3ee; margin:0 0 10px;">
                                    Email
                                </p>

                                <p style="color:white; margin:0 0 24px;">
                                    {{ $data['email'] }}
                                </p>

                                <p style="color:#22d3ee; margin:0 0 10px;">
                                    Phone
                                </p>

                                <p style="color:white; margin:0 0 24px;">
                                    {{ $data['phone'] }}
                                </p>

                                <p style="color:#22d3ee; margin:0 0 10px;">
                                    Message
                                </p>

                                <p style="
                                    color:white;
                                    margin:0;
                                    line-height:1.7;
                                ">
                                    {{ $data['message'] }}
                                </p>

                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td style="
                            padding:24px;
                            text-align:center;
                            border-top:1px solid rgba(255,255,255,0.08);
                        ">

                            <p style="
                                color:#64748b;
                                font-size:13px;
                                margin:0;
                            ">
                                Afolio • Laravel + Vue + AI
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>