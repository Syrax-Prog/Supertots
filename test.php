package com.example.supertots;

import androidx.appcompat.app.AppCompatActivity;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.graphics.Color;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.view.View;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.IOException;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;

public class Progress extends AppCompatActivity {

    private static final String TAG = "ProgressActivity";
    private TableLayout tableLayout;
    private String parentId;
    private static final String BASE_URL = "http://192.168.1.249/supertots/android/";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_progress);

        parentId = getIntent().getStringExtra("parent_id");
        Log.d(TAG, "Parent ID: " + parentId);

        tableLayout = findViewById(R.id.tableLayout);

        new GetProgressTask().execute();

        setupButtonListeners();
    }

    private class GetProgressTask extends AsyncTask<Void, Void, String> {
        OkHttpClient client = new OkHttpClient();

        @Override
        protected String doInBackground(Void... voids) {
            String url = BASE_URL + "get_progress.php?parent_id=" + parentId;
            Log.d(TAG, "Request URL: " + url);

            Request request = new Request.Builder()
                    .url(url)
                    .build();

            try {
                Response response = client.newCall(request).execute();
                return response.body().string();
            } catch (IOException e) {
                e.printStackTrace();
                return null;
            }
        }

        @Override
        protected void onPostExecute(String result) {
            if (result != null) {
                try {
                    JSONArray jsonArray = new JSONArray(result);
                    displayProgressInTable(jsonArray);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }
    }

    private void displayProgressInTable(JSONArray jsonArray) throws JSONException {
        // Create and style table header row
        TableRow headerRow = new TableRow(this);
        headerRow.setBackgroundColor(getResources().getColor(R.color.colorPrimaryDark));
        headerRow.setPadding(16, 16, 16, 16);

        TableRow.LayoutParams headerParams = new TableRow.LayoutParams(
                0, // width
                TableRow.LayoutParams.WRAP_CONTENT, // height
                1f // weight
        );
        headerParams.setMargins(8, 8, 8, 8);

        TextView header1 = new TextView(this);
        header1.setLayoutParams(headerParams);
        header1.setText("Exam Type");
        header1.setGravity(Gravity.CENTER);
        header1.setTextColor(Color.WHITE);
        header1.setPadding(8, 8, 8, 8);
        headerRow.addView(header1);

        TextView header2 = new TextView(this);
        header2.setLayoutParams(headerParams);
        header2.setText("Date");
        header2.setGravity(Gravity.CENTER);
        header2.setTextColor(Color.WHITE);
        header2.setPadding(8, 8, 8, 8);
        headerRow.addView(header2);

        TextView header3 = new TextView(this);
        header3.setLayoutParams(headerParams);
        header3.setText("Marks");
        header3.setGravity(Gravity.CENTER);
        header3.setTextColor(Color.WHITE);
        header3.setPadding(8, 8, 8, 8);
        headerRow.addView(header3);

        tableLayout.addView(headerRow);

        // Create and style data rows
        for (int i = 0; i < jsonArray.length(); i++) {
            JSONObject jsonObject = jsonArray.getJSONObject(i);
            String examType = jsonObject.getString("examType");
            String examDate = jsonObject.getString("examDate");
            int examMarks = jsonObject.getInt("examMarks");

            TableRow row = new TableRow(this);
            row.setBackgroundColor(i % 2 == 0 ? Color.LTGRAY : Color.WHITE);
            row.setPadding(16, 16, 16, 16);

            TableRow.LayoutParams params = new TableRow.LayoutParams(
                    0, // width
                    TableRow.LayoutParams.WRAP_CONTENT, // height
                    1f // weight
            );
            params.setMargins(8, 8, 8, 8);

            TextView tv1 = new TextView(this);
            tv1.setLayoutParams(params);
            tv1.setText(examType);
            tv1.setGravity(Gravity.CENTER);
            tv1.setPadding(8, 8, 8, 8);
            row.addView(tv1);

            TextView tv2 = new TextView(this);
            tv2.setLayoutParams(params);
            tv2.setText(examDate);
            tv2.setGravity(Gravity.CENTER);
            tv2.setPadding(8, 8, 8, 8);
            row.addView(tv2);

            TextView tv3 = new TextView(this);
            tv3.setLayoutParams(params);
            tv3.setText(String.valueOf(examMarks));
            tv3.setGravity(Gravity.CENTER);
            tv3.setPadding(8, 8, 8, 8);
            row.addView(tv3);

            tableLayout.addView(row);
        }
    }

    private void setupButtonListeners() {
        ImageButton btnHamburger = findViewById(R.id.btn_hamburger);
        ImageButton btnBack = findViewById(R.id.btn_back);

        btnHamburger.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                LinearLayout dropdownMenu = findViewById(R.id.dropdown_menu);
                if (dropdownMenu.getVisibility() == View.GONE) {
                    dropdownMenu.setVisibility(View.VISIBLE);
                } else {
                    dropdownMenu.setVisibility(View.GONE);
                }
            }
        });

        btnBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                onBackPressed();
            }
        });

        TextView menuHome = findViewById(R.id.menu_home);
        TextView menuProfile = findViewById(R.id.menu_profile);
        TextView menuChildActivity = findViewById(R.id.menu_child_activity);
        TextView menuChat = findViewById(R.id.menu_chat);
        TextView menuLogout = findViewById(R.id.menu_logout);

        menuHome.setOnClickListener(menuItemClickListener);
        menuProfile.setOnClickListener(menuItemClickListener);
        menuChildActivity.setOnClickListener(menuItemClickListener);
        menuChat.setOnClickListener(menuItemClickListener);
        menuLogout.setOnClickListener(menuItemClickListener);
    }

    private View.OnClickListener menuItemClickListener = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            Intent intent;
            int id = v.getId();
            if (id == R.id.menu_home) {
                intent = new Intent(Progress.this, Homepage.class);
                intent.putExtra("parent_id", parentId); // Pass the parent ID back to Homepage
            } else if (id == R.id.menu_profile) {
                intent = new Intent(Progress.this, Profile.class);
            } else if (id == R.id.menu_child_activity) {
                intent = new Intent(Progress.this, Homepage.class); // Placeholder, replace with actual activity
            } else if (id == R.id.menu_chat) {
                intent = new Intent(Progress.this, Homepage.class); // Placeholder, replace with actual activity
            } else if (id == R.id.menu_logout) {
                // Implement logout functionality
                return;
            } else {
                return;
            }
            startActivity(intent);
            LinearLayout dropdownMenu = findViewById(R.id.dropdown_menu);
            dropdownMenu.setVisibility(View.GONE);
        }
    };
}
