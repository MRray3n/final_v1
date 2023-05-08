package com.example.chatbotv0;


import static com.example.chatbotv0.UniqueIdGenerator.getUniqueId;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.text.Html;
import android.text.SpannableString;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;

import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;


import java.io.UnsupportedEncodingException;
import java.net.NetworkInterface;

import java.util.ArrayList;

import java.net.URLDecoder;

import android.content.Context;
import android.content.SharedPreferences;


import java.util.Collections;
import java.util.Date;
import java.util.List;

import android.text.util.Linkify;


public class MainActivity extends AppCompatActivity {

    private EditText userMsgEdt;
    private final String BOT_KEY = "bot";
    //creating a variable for array list and adapter class.
    private ArrayList<MessageModal> messageModalArrayList;
    private MessageRVAdapter messageRVAdapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        //on below line we are initializing all our views.
        //creating variables for our widgets in xml file.
        RecyclerView chatsRV = findViewById(R.id.idRVChats);
        ImageButton sendMsgIB = findViewById(R.id.idIBSend);
        userMsgEdt = findViewById(R.id.idEdtMessage);
        //below line is to initialize our request queue.
        //creating a variable for our volley request queue.
        RequestQueue mRequestQueue = Volley.newRequestQueue(MainActivity.this);
        mRequestQueue.getCache().clear();
        //creating a new array list
        messageModalArrayList = new ArrayList<>();
        //adding on click listener for send message button.
        sendMsgIB.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //checking if the message entered by user is empty or not.
                if (userMsgEdt.getText().toString().isEmpty()) {
                    //if the edit text is empty display a toast message.
                    Toast.makeText(MainActivity.this, "Please enter your message..", Toast.LENGTH_SHORT).show();
                    return;
                }
                //calling a method to send message to our bot to get response.
                sendMessage(userMsgEdt.getText().toString());
                //below line we are setting text in our edit text as empty
                userMsgEdt.setText("");

            }
        });

        //on below line we are initialiing our adapter class and passing our array lit to it.
        messageRVAdapter = new MessageRVAdapter(messageModalArrayList, this);
        //below line we are creating a variable for our linear layout manager.
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(MainActivity.this, RecyclerView.VERTICAL, false);
        //below line is to set layout manager to our recycler view.
        chatsRV.setLayoutManager(linearLayoutManager);
        //below line we are setting adapter to our recycler view.
        chatsRV.setAdapter(messageRVAdapter);
    }


    private void sendMessage(String userMsg) {

        //below line is to pass message to our array list which is entered by the user.
        String USER_KEY = "user";
        messageModalArrayList.add(new MessageModal(userMsg, USER_KEY));
        messageRVAdapter.notifyDataSetChanged();
        //url for our brain
        //make sure to add mshape for uid.
        String uniqueId = getUniqueId(this);
        System.out.println(uniqueId);

        String url = "http://172.16.1.1:7777/get?msg=" + userMsg + "&userid=" + uniqueId;;
        //creating a variable for our request queue.
        RequestQueue queue = Volley.newRequestQueue(MainActivity.this);
        //on below line we are making a json object request for a get request and passing our url .
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, url, null, new Response.Listener<JSONObject>() {

            @Override
            public void onResponse(JSONObject response) {
                try {
                    //in on response method we are extracting data from json response and adding this response to our array list.
                    String botResponse = response.getString("cnt");
                    String decodedMessage = URLDecoder.decode(botResponse.replaceAll("\\+", "%20"), "UTF-8");
                    System.out.println(decodedMessage);
                    messageModalArrayList.add(new MessageModal(decodedMessage, BOT_KEY));
                    //notifying our adapter as data changed.
                    messageRVAdapter.notifyDataSetChanged();
                } catch (JSONException e) {
                    e.printStackTrace();
                    //handling error response from bot.
                    messageModalArrayList.add(new MessageModal("No response", BOT_KEY));
                    messageRVAdapter.notifyDataSetChanged();

                } catch (UnsupportedEncodingException e) {
                    throw new RuntimeException(e);
                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                //error handling.
                messageModalArrayList.add(new MessageModal("Sorry no response found", BOT_KEY));
                Toast.makeText(MainActivity.this, "No response from the bot..", Toast.LENGTH_SHORT).show();
            }
        });
        //at last adding json object request to our queue.
        queue.add(jsonObjectRequest);


    }
}
class UniqueIdGenerator {

    private static final String PREF_UNIQUE_ID = "PREF_UNIQUE_ID";
    private static String uniqueId = null;

    public synchronized static String getUniqueId(Context context) {
        if (uniqueId == null) {
            SharedPreferences sharedPrefs = context.getSharedPreferences(
                    PREF_UNIQUE_ID, Context.MODE_PRIVATE);
            uniqueId = sharedPrefs.getString(PREF_UNIQUE_ID, null);
            if (uniqueId == null) {
                String macAddress = getMacAddress();
                String androidId = getAndroidId(context);
                Date timestamp = new Date();
                uniqueId = macAddress + "_" + androidId + "_" + timestamp.getTime();
                SharedPreferences.Editor editor = sharedPrefs.edit();
                editor.putString(PREF_UNIQUE_ID, uniqueId);
                editor.commit();
            }
        }
        return uniqueId;
    }
    private static String getMacAddress() {
        try {
            List<NetworkInterface> interfaces = Collections.list(NetworkInterface.getNetworkInterfaces());
            for (NetworkInterface intf : interfaces) {
                byte[] mac = intf.getHardwareAddress();
                if (mac == null) {
                    continue;
                }
                StringBuilder buf = new StringBuilder();
                for (byte aMac : mac) {
                    buf.append(String.format("%02X:", aMac));
                }
                if (buf.length() > 0) {
                    buf.deleteCharAt(buf.length() - 1);
                }
                String macAddress = buf.toString();
                if (macAddress != null && !macAddress.equals("02:00:00:00:00:00")) {
                    return macAddress;
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }

    static String getAndroidId(Context context) {
        String androidId = Settings.Secure.getString(context.getContentResolver(),
                Settings.Secure.ANDROID_ID);
        if (androidId == null) {
            androidId = Build.SERIAL;
        }
        return androidId;
    }
}